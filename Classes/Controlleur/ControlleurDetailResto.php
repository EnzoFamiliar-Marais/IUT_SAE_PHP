<?php
namespace Controlleur;

use Auth\DBRestaurant;
use Auth\DBCritique;
use Auth\DBAuth;
use Auth\DBCommune;
use Auth\DBDepartement;
use Auth\DBRegion;
use Auth\DBFavoris;
use form\Form;
use form\type\Link;
use form\type\Select;
use form\type\Submit;
use form\type\Hidden;
use form\type\Text;

class ControlleurDetailResto extends Controlleur
{
    private function processOpeningHours($openingHours) {
        if (!$openingHours) {
            return null;
        }

        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $processed = [];
        
        // Format attendu: "Mo-Fr 08:00-22:00; Sa-Su 10:00-20:00"
        $schedules = explode(';', $openingHours);
        
        foreach ($schedules as $schedule) {
            $schedule = trim($schedule);
            if (empty($schedule)) continue;
            
            // Séparer les jours et les heures
            $parts = explode(' ', $schedule);
            if (count($parts) < 2) continue;
            
            $dayRange = $parts[0];
            $timeRange = $parts[1];
            
            // Convertir les abréviations en jours complets
            $dayMap = [
                'Mo' => 'Lundi',
                'Tu' => 'Mardi',
                'We' => 'Mercredi',
                'Th' => 'Jeudi',
                'Fr' => 'Vendredi',
                'Sa' => 'Samedi',
                'Su' => 'Dimanche'
            ];
            
            // Gérer les plages de jours (ex: Mo-Fr)
            if (strpos($dayRange, '-') !== false) {
                list($start, $end) = explode('-', $dayRange);
                $startIdx = array_search($dayMap[$start], $days);
                $endIdx = array_search($dayMap[$end], $days);
                
                if ($startIdx !== false && $endIdx !== false) {
                    for ($i = $startIdx; $i <= $endIdx; $i++) {
                        $processed[$days[$i]][] = $timeRange;
                    }
                }
            } else {
                // Jour unique
                if (isset($dayMap[$dayRange])) {
                    $processed[$dayMap[$dayRange]][] = $timeRange;
                }
            }
        }
        
        // Vérifier si le restaurant est ouvert maintenant
        $currentDay = date('N') - 1; // 0 (Lundi) à 6 (Dimanche)
        $currentTime = date('H:i');
        $isOpenNow = false;
        
        if (isset($processed[$days[$currentDay]])) {
            foreach ($processed[$days[$currentDay]] as $timeRange) {
                list($open, $close) = explode('-', $timeRange);
                if ($currentTime >= $open && $currentTime <= $close) {
                    $isOpenNow = true;
                    break;
                }
            }
        }
        
        return [
            'hours' => $processed,
            'is_open_now' => $isOpenNow
        ];
    }

    public function view()
    {
        if (!isset($_GET['id'])) {
            $this->redirect("ControlleurResto", "view");
            return;
        }

        $restaurantId = (int)$_GET['id'];
        $dbRestaurant = new DBRestaurant();
        $restaurant = $dbRestaurant->getRestaurantById($restaurantId);

        if (!$restaurant) {
            $this->redirect("ControlleurResto", "view");
            return;
        }

        // Traiter les horaires d'ouverture
        $openingHoursData = $this->processOpeningHours($restaurant['opening_hours']);
        if ($openingHoursData) {
            $restaurant['opening_hours_processed'] = $openingHoursData['hours'];
            $restaurant['is_open_now'] = $openingHoursData['is_open_now'];
        }

        $dbCritique = new DBCritique();
        $critiques = $dbCritique->getCritiqueByRestaurant($restaurantId);

        $isFavoris = false;
        $userId = $_SESSION['auth'] ?? null;
        if ($userId) {
            error_log("Checking favorites for user $userId and restaurant $restaurantId");
            $dbFavoris = new DBFavoris();
            $isFavoris = $dbFavoris->isFavoris($userId, $restaurantId);
            error_log("Is favorite: " . ($isFavoris ? "yes" : "no"));
        }

        // Récupérer les informations de localisation
        $dbCommune = new DBCommune();
        $dbDepartement = new DBDepartement();
        $dbRegion = new DBRegion();

        $commune = null;
        $departement = null;
        $region = null;

        if (isset($restaurant['idCommune'])) {
            $communeData = $dbCommune->getCommuneById($restaurant['idCommune']);
            if ($communeData) {
                $commune = $communeData['nom'];
                $departementData = $dbDepartement->getDepartementById($communeData['idD']);
                if ($departementData) {
                    $departement = $departementData['nom'];
                    $regionData = $dbRegion->getRegionById($departementData['idR']);
                    if ($regionData) {
                        $region = $regionData['nom'];
                    }
                }
            }
        }

        $this->render("details_resto.php", [
            "restaurant" => $restaurant,
            "critiques" => $critiques,
            "formDeconnexion" => $this->getFormDeconnexion(),
            "isFavoris" => $isFavoris,
            "userId" => $userId,
            "commune" => $commune,
            "departement" => $departement,
            "region" => $region
        ]);
    }

    public function toggleFavoris()
    {
        if (!isset($_SESSION['auth']) || !isset($_GET['id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $userId = $_SESSION['auth'];
        $restaurantId = (int)$_GET['id'];
        $dbFavoris = new DBFavoris();
        
        error_log("Toggling favorite for user $userId and restaurant $restaurantId");
        
        $isFavoris = $dbFavoris->isFavoris($userId, $restaurantId);
        error_log("Current favorite status: " . ($isFavoris ? "yes" : "no"));

        $success = false;
        if ($isFavoris) {
            $success = $dbFavoris->removeFavoris($userId, $restaurantId);
            error_log("Tried to remove favorite, success: " . ($success ? "yes" : "no"));
        } else {
            $success = $dbFavoris->addFavoris($userId, $restaurantId);
            error_log("Tried to add favorite, success: " . ($success ? "yes" : "no"));
        }

        $newStatus = $dbFavoris->isFavoris($userId, $restaurantId);
        error_log("New favorite status: " . ($newStatus ? "yes" : "no"));

        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'isFavoris' => $newStatus
        ]);
        exit;
    }

    public function submit()
    {
        $auth = new DBAuth();
        $auth->logout();
        $this->redirect("ControlleurDetailResto", "view");
    }

    public function submitAvis()
    {
        if (!isset($_SESSION['auth']) || !isset($_GET['id'])) {
            $this->redirect("ControlleurDetailResto", "view");
            return;
        }

        $dbCritique = new DBCritique();
        $userId = $_SESSION['auth'];
        $restaurantId = (int)$_GET['id'];
        $note = $_POST['note'];
        $commentaire = $_POST['commentaire'];

        $dbCritique->addCritique($userId, $restaurantId, $note, $commentaire);
        header("Location: /?controller=ControlleurDetailResto&action=view&id=" . urlencode($restaurantId));
        exit;
    }

    public function getFormDeconnexion()
    {
        $form = new Form("/?controller=ControlleurDetailResto&action=submit", Form::GET, "home_form");
        $form->setController("ControlleurDetailResto", "submit");
        $form->addInput(new Submit("Deconnexion", true, "", ""));
        return $form;
    }
}
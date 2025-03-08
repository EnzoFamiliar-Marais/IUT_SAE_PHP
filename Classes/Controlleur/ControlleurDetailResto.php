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

        $_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];

        error_log(print_r($_GET, true));
        error_log("id_resto : ".$_GET["id"]);
        $dbRestaurant = new DBRestaurant();
        $dbCommune = DBCommune::getAllCommunes();
        $dbRegion = DBRegion::getAllRegions();
        $dbDepartement = DBDepartement::getAllDepartements();
        $restaurant =  $dbRestaurant->getRestaurantById($_GET["id"]);
        
        $dbCritique = new DBCritique();
        $critiques = $dbCritique->getCritiqueByRestaurant($_GET["id"]);
        $commune = null;
        $region = null;
        $departement = null;

        if ($restaurant != null) {
            foreach ($dbCommune as $communeItem) {
                if ($communeItem["idC"] == $restaurant["idCommune"]) {
                    error_log("commune : " . $communeItem["nom"]);
                    $commune = $communeItem["nom"];
                    $idDepartement = $communeItem["idD"];
                    break;
                }
            }foreach ($dbDepartement as $departementItem) {
                if ($departementItem["idD"] == $idDepartement) {
                    error_log("departement : " . $departementItem["nom"]);
                    $departement = $departementItem["nom"];
                    $idRegion = $departementItem["idR"];
                    break;
                }
            }

            foreach ($dbRegion as $regionItem) {
                if ($regionItem["idR"] == $idRegion) {
                    error_log("region : " . $regionItem["nom"]);
                    $region = $regionItem["nom"];
                    break;
                }
            }
            $restaurant = $this->renderHoraire($restaurant);
        }

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

        
        error_log(print_r($restaurant, true));
        $this->render("details_resto.php", [
            "restaurant" => $restaurant,
            "commune" => $commune,
            "region" => $region,
            "departement" => $departement,
            "formDeconnexion" => $this->getFormDeconnexion(),
            "isFavoris" => $isFavoris,
            "userId" => $userId,
            "commune" => $commune,
            "departement" => $departement,
            "region" => $region
        ]);
            "critiques" => $critiques,
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
        $dbRestaurant = new DBRestaurant();

        if(isset($_POST) && $_SESSION['auth']){
            $dbCritique->addCritique($_SESSION['auth'], $_POST["id"], $_POST["note"], $_POST['content']);
        }
        $this->redirect("ControlleurDetailResto", "view", $_POST["id"]);


    }

    public function getFormDeconnexion()
    {
        $form = new Form("/?controller=ControlleurDetailResto&action=submit", Form::GET, "home_form");
        $form->setController("ControlleurDetailResto", "submit");
        $form->addInput(new Submit("Deconnexion", true, "", ""));
        return $form;
    }

    
    public function renderHoraire($restaurant) {
        if ($restaurant["opening_hours"] != null) {
            $days = ["Mo" => "Lundi", "Tu" => "Mardi", "We" => "Mercredi", "Th" => "Jeudi", "Fr" => "Vendredi", "Sa" => "Samedi", "Su" => "Dimanche"];
            $hours = explode(';', $restaurant["opening_hours"]);
            $opening_hours = [];
    
            foreach ($hours as $hour) {
                list($day_range, $time) = explode(' ', trim($hour), 2);
                $day_range_parts = explode('-', $day_range);
    
                if (count($day_range_parts) == 2) {
                    $start_day = $day_range_parts[0];
                    $end_day = $day_range_parts[1];
    
                    $start_index = array_search($start_day, array_keys($days));
                    $end_index = array_search($end_day, array_keys($days));
    
                    if ($start_index !== false && $end_index !== false) {
                        for ($i = $start_index; $i <= $end_index; $i++) {
                            $day_abbr = array_keys($days)[$i];
                            $day = $days[$day_abbr];
    
                            if (!isset($opening_hours[$day])) {
                                $opening_hours[$day] = [];
                            }
                            $opening_hours[$day][] = $time;
                        }
                    }
                } else {
                    if (isset($days[$day_range])) {
                        $day = $days[$day_range];
                        if (!isset($opening_hours[$day])) {
                            $opening_hours[$day] = [];
                        }
                        $opening_hours[$day][] = $time;
                    }
                }
            }
    
            $restaurant["opening_hours_processed"] = $opening_hours;
    
            $todayIndex = date('N'); 
            $today = array_values($days)[$todayIndex - 1];
    
            $currentTime = date('H:i');
            $isOpenNow = false;
    
            if (isset($restaurant["opening_hours_processed"][$today])) {
                foreach ($restaurant["opening_hours_processed"][$today] as $timeRange) {
                    list($startTime, $endTime) = explode('-', $timeRange);
    
                    $startTime = trim($startTime);
                    $endTime = trim($endTime);
    
                    if ($currentTime >= $startTime && $currentTime <= $endTime) {
                        $isOpenNow = true;
                        break;
                    }
                }
            }
    
            $restaurant["is_open_now"] = $isOpenNow;
        }
    
        return $restaurant;
    }

}
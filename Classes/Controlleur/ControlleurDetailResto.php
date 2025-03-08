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
use form\type\Submit;

class ControlleurDetailResto extends Controlleur
{
    private function processOpeningHours($openingHours) {
        if (!$openingHours) {
            return null;
        }

        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $processed = [];
        
        $schedules = explode(';', $openingHours);
        
        foreach ($schedules as $schedule) {
            $schedule = trim($schedule);
            if (empty($schedule)) continue;
            
            $parts = explode(' ', $schedule);
            if (count($parts) < 2) continue;
            
            $dayRange = $parts[0];
            $timeRange = $parts[1];
            
            $dayMap = [
                'Mo' => 'Lundi',
                'Tu' => 'Mardi',
                'We' => 'Mercredi',
                'Th' => 'Jeudi',
                'Fr' => 'Vendredi',
                'Sa' => 'Samedi',
                'Su' => 'Dimanche'
            ];
            
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
                if (isset($dayMap[$dayRange])) {
                    $processed[$dayMap[$dayRange]][] = $timeRange;
                }
            }
        }
        
        $currentDay = date('N') - 1;
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
        $_SESSION['error_message'] = "Restaurant not found.";
        $this->redirect("ControlleurResto", "view");
        return;
    }

    // Process opening hours
    $openingHoursData = $this->processOpeningHours($restaurant['opening_hours']);
    if ($openingHoursData) {
        $restaurant['opening_hours_processed'] = $openingHoursData['hours'];
        $restaurant['is_open_now'] = $openingHoursData['is_open_now'];
    }

    $_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];

    $dbCommune = DBCommune::getAllCommunes();
    $dbRegion = DBRegion::getAllRegions();
    $dbDepartement = DBDepartement::getAllDepartements();
    $critiques = (new DBCritique())->getCritiqueByRestaurant($restaurantId);

    // Get location details
    list($commune, $region, $departement) = $this->getLocationDetails($restaurant, $dbCommune, $dbDepartement, $dbRegion);

    // Handle favorites
    $isFavoris = $this->isFavoris($restaurantId);

    $this->render("details_resto.php", [
        "restaurant" => $restaurant,
        "critiques" => $critiques,
        "commune" => $commune,
        "region" => $region,
        "departement" => $departement,
        "formDeconnexion" => $this->getFormDeconnexion(),
        "isFavoris" => $isFavoris,
        "userId" => $_SESSION['auth'] ?? null
    ]);
}

// Utility to get location details
private function getLocationDetails($restaurant, $dbCommune, $dbDepartement, $dbRegion)
{
    $commune = $region = $departement = null;
    foreach ($dbCommune as $communeItem) {
        if ($communeItem["idC"] == $restaurant["idCommune"]) {
            $commune = $communeItem["nom"];
            $idDepartement = $communeItem["idD"];
            break;
        }
    }
    foreach ($dbDepartement as $departementItem) {
        if ($departementItem["idD"] == $idDepartement) {
            $departement = $departementItem["nom"];
            $idRegion = $departementItem["idR"];
            break;
        }
    }
    foreach ($dbRegion as $regionItem) {
        if ($regionItem["idR"] == $idRegion) {
            $region = $regionItem["nom"];
            break;
        }
    }

    return [$commune, $region, $departement];
}

private function isFavoris($restaurantId)
{
    if (!isset($_SESSION['auth'])) return false;

    $dbFavoris = new DBFavoris();
    return $dbFavoris->isFavoris($_SESSION['auth'], $restaurantId);
}


    public function toggleFavoris()
    {
        if (!isset($_SESSION['auth']) || !isset($_GET['id'])) {
            error_log('Utilisateur non authentifié ou ID du restaurant manquant');
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

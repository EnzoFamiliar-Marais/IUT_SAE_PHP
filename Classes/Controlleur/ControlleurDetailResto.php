<?php
namespace Controlleur;

use Auth\DBRestaurant;
use Auth\DBCritique;
use Auth\DBAuth;
use Auth\DBCommune;
use Auth\DBDepartement;
use Auth\DBRegion;
use form\Form;
use form\type\Link;
use form\type\Select;
use form\type\Submit;
use form\type\Hidden;
use form\type\Text;

class ControlleurDetailResto extends Controlleur
{
    public function view()
    {
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

        
        error_log(print_r($restaurant, true));
        $this->render("details_resto.php", [
            "restaurant" => $restaurant,
            "commune" => $commune,
            "region" => $region,
            "departement" => $departement,
            "formDeconnexion" => $this->getFormDeconnexion(),
            "critiques" => $critiques,
    ]);
     
    }

    public function submit()
    {
        $auth = new DBAuth();

        $auth->logout();
        $this->redirect("ControlleurDetailResto", "view");
    }

    public function submitAvis()
    {
        $dbCritique = new DBCritique();
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
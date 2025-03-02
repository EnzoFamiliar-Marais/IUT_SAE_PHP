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

class ControleurDetailResto extends Controlleur
{
   
    public function view()
    {
            
                

                error_log(print_r($_GET, true));
                error_log("id_resto : ".$_GET["id"]);
                $dbRestaurant = new DBRestaurant();
                $dbCommune = DBCommune::getAllCommunes();
                $dbRegion = DBRegion::getAllRegions();
                $dbDepartement = DBDepartement::getAllDepartements();
                $restaurant =  $dbRestaurant->getRestaurantById($_GET["id"]);
                

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
                    $restaurant = $this->viewDetails($restaurant);
                }

                
                error_log(print_r($restaurant, true));
                $this->render("details_resto.php", [
                    "restaurant" => $restaurant,
                    "commune" => $commune,
                    "region" => $region,
                    "departement" => $departement,
                    "formDeconnexion" => $this->getFormDeconnexion(),
                    //"formResto" => $this->getFormResto(),
            ]);
        }
        

    public function submit()
    {
        $auth = new DBAuth();
        $auth->logout();
        $this->redirect("ControlleurHome", "view");
    }

    public function getFormDeconnexion()
    {
        $form = new Form("/?controller=ControlleurHome&action=submit", Form::GET, "home_form");
        $form->setController("ControlleurHome", "submit");
        $form->addInput(new Submit("Deconnexion", true, "", ""));
        return $form;
    }

    public function getFormDeleteAdmin($id){ 
        $forms = new Form("/?controller=ControlleurCritique&action=submitDelete", Form::POST, "admin_form");
        $forms->setController("ControlleurCritique", "submitDelete");
        $forms->addInput(new Hidden($id,true, "critique_id", "critique_id")); 
        $forms->addInput(new Submit("Supprimer", true, "", "", ""));
        
        return $forms;
        }
    

    
        public function viewDetails($restaurant) {
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
        
                        for ($i = $start_index; $i <= $end_index; $i++) {
                            $day_abbr = array_keys($days)[$i];
                            $day = $days[$day_abbr];
                            if (!isset($opening_hours[$day])) {
                                $opening_hours[$day] = [];
                            }
                            $opening_hours[$day][] = $time;
                        }
                    } else {
                        $day = $days[$day_range];
                        if (!isset($opening_hours[$day])) {
                            $opening_hours[$day] = [];
                        }
                        $opening_hours[$day][] = $time;
                    }
                }
        
                $restaurant["opening_hours_processed"] = $opening_hours;
            }
        
            return $restaurant;
        }
    
    

}
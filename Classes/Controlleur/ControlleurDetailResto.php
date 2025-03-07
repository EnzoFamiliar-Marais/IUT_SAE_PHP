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
        if (!isset($_GET['id'])) {
            $this->redirect("ControlleurResto", "view");
        }


                $commune = null;
                $region = null;
                $departement = null;
        
                if ($restaurant != null) {
                    foreach ($dbCommune as $communeItem) {
                        if ($communeItem["idC"] == $restaurant["idCommune"]) {
                            $commune = $communeItem["nom"];
                            $idDepartement = $communeItem["idD"];
                            break;
                        }
                    }foreach ($dbDepartement as $departementItem) {
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
                    $restaurant = $this->renderHoraire($restaurant);
                }

                
                $this->render("details_resto.php", [
                    "restaurant" => $restaurant,
                    "commune" => $commune,
                    "region" => $region,
                    "departement" => $departement,
                    "formDeconnexion" => $this->getFormDeconnexion(),

                  
        $restaurantId = $_GET['id'];
        $dbRestaurant = new DBRestaurant();
        $restaurant = $dbRestaurant->getRestaurantById($restaurantId);

        $dbCritique = new DBCritique();
        $critiques = $dbCritique->getCritiqueByRestaurant($restaurantId);


        $this->render("details_resto.php", [
            "restaurant" => $restaurant,
            "critiques" => $critiques,
            "formDeconnexion" => $this->getFormDeconnexion(),
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
}
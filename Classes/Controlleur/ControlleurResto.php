<?php
namespace Controlleur;

use Auth\DBRestaurant;
use Auth\DBAuth;
use Auth\DBPropose;
use Auth\DBTypeCuisine;
use form\Form;
use form\type\Link;
use form\type\Select;
use form\type\Submit;
use form\type\Text;
use form\type\Input;

class ControlleurResto extends Controlleur
{
   
    public function view()
    {   

        $_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
        
            $restaurants = DBRestaurant::getAllRestaurant();
            $propositions = DBPropose::getAllProposes();
            $typeCuisines = DBTypeCuisine::getAllTypeCuisine();
            error_log(print_r($_GET, true));
            if(isset($_GET) && isset($_GET["id"])){
                
            }
                $this->render("resto.php", [
                    "restaurants" => $restaurants,
                    "propositions" => $propositions,
                    "typeCuisines" => $typeCuisines,
                    "formDeconnexion" => $this->getFormDeconnexion(),
                    'formRecherche' => $this->getFormRecherche(),
                    "filtreCuisine" => $this->getFormFiltreTypeCuisine(),
                    "filtreTypeRestaurant" => $this->getFormFiltreTypeRestaurant(),
                   
            ]);
        }

        
        

    public function submit()
    {
        $auth = new DBAuth();
        $auth->logout();
        $this->redirect("ControlleurResto", "view");
    }


    public function getFormRecherche(){
        $form = new Form("/?controller=ControlleurHome&action=view", "", "home_form");
        $form->addInput(new Text("", true, "recherche", "recherche", "filtrages()", "", "oninput", false, "Ville, nom restaurant, etc..."));
        return $form;
    }


    public function getFormFiltreTypeCuisine(){
        $form = new Form("/?controller=ControlleurHome&action=view", "", "home_form");
        $select = new Select("", false, "TypeCuisine", "TypeCuisine", "filtrages()", "Type de Cuisine", "onchange");
        $select->addOption("", "Type de Cuisine");
        
        $dbTypeCuisine = new DBTypeCuisine();
        $typeCuisines = $dbTypeCuisine->getAllTypeCuisine();

        foreach($typeCuisines as $typeCuisine){
            $select->addOption($typeCuisine['nom'], $typeCuisine['nom']);
        }
        $form->addInput($select);
        
        return $form;
    }


    public function getFormFiltreTypeRestaurant(){
        $form = new Form("/?controller=ControlleurHome&action=view", "", "home_form");
        $select = new Select("", false, "TypeRestaurant", "TypeRestaurant", "filtrages()", "Type de TypeRestaurant", "onchange");
    
    
        $select->addOption("", "Type de restaurant");
            
        $dbTypeRestaurant = DBRestaurant::getAllRestaurant();
        
        $uniqueTypes = [];
        foreach($dbTypeRestaurant as $typeRestaurant){
            if (!in_array($typeRestaurant['type'], $uniqueTypes)) {
            $uniqueTypes[] = $typeRestaurant['type'];
            $select->addOption($typeRestaurant['type'], $typeRestaurant['type']);
            }
        }
        $form->addInput($select);
        
        return $form;
    }

    public function getFormDeconnexion()
    {
        $form = new Form("/?controller=ControlleurResto&action=submit", Form::GET, "home_form");
        $form->setController("ControlleurResto", "submit");


        $form->addInput(new Submit("Deconnexion", true, "", ""));
        return $form;
    }


}   
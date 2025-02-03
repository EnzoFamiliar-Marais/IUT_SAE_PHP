<?php
namespace Controlleur;

use Auth\DBRestaurant;
use Auth\DBAuth;
use form\Form;
use form\type\Link;
use form\type\Select;
use form\type\Submit;
use form\type\Text;

class ControlleurResto extends Controlleur
{
   
    public function view()
    {
        
            $restaurants = DBRestaurant::getAllRestaurant();
            

          
                $this->render("resto.php", [
                    "restaurants" => $restaurants,
                    'formRecherche' => $this->getFormRecherche(),
                    "filtreCuisine" => $this->getFormFiltreTypeCuisine(),
                    "filtreTypeRestaurant" => $this->getFormFiltreTypeRestaurant(),
                   
            ]);
        }
        

    public function submit()
    {
        $auth = new DBAuth();
        $auth->logout();
        $this->redirect("ControlleurLogin", "view");
    }

    public function getFormRegister()
    {   
        $form = new Form("/?controller=ControlleurHome&action=view", Form::GET, "home_form");
        $form->setController("ControlleurHome", "submit");
        $form->addInput(new Link("/?controller=ControlleurRegister&action=view", "Register"));
        return $form;
    }

    public function getFormRecherche(){
        $form = new Form("/?controller=ControlleurHome&action=view", "", "home_form");
        $form->addInput(new Text("", true, "recherche", "recherche", "filtrages()", "", "oninput", false, "Ville, nom restaurant, etc..."));
        return $form;
    }


    public function getFormFiltreTypeCuisine(){
        $form = new Form("/?controller=ControlleurHome&action=view", "", "home_form");
        $select = new Select("", false, "TypeCuisine", "TypeCuisine", "filtrages()", "Type de Cuisine", "onchange");
    
        // Ajout des options
        $select->addOption("", "Type de Cuisine")
               ->addOption("italienne", "Italienne")
               ->addOption("francaise", "Française")
               ->addOption("chinoise", "Chinoise")
               ->addOption("japonaise", "Japonaise");
    
        $form->addInput($select);
        
        return $form;
    }


    public function getFormFiltreTypeRestaurant(){
        $form = new Form("/?controller=ControlleurHome&action=view", "", "home_form");
        $select = new Select("", false, "TypeRestaurant", "TypeRestaurant", "filtrages()", "Type de TypeRestaurant", "onchange");
    
        // Ajout des options
        $select->addOption("", "Type de restaurant")
               ->addOption("fast-food", "Fast-Food")
               ->addOption("bar", "Bar")
               ->addOption("restaurant", "Restaurant")
               ->addOption("pub", "Pub");
    
        $form->addInput($select);
        
        return $form;
    }

}   
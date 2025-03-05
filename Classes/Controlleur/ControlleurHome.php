<?php
namespace Controlleur;

use Auth\DBRestaurant;
use Auth\DBAuth;
use Auth\DBCritique;
use form\Form;
use form\type\Link;
use form\type\Select;
use form\type\Submit;
use form\type\Text;

class ControlleurHome extends Controlleur
{
   
    public function view()
    {
        
            $bestrestaurants = null;
            
            if($_SESSION['id_role'] == 1){
                $this->redirect("ControlleurAdmin", "view");

            }else{
                $this->render("home.php", [
                    "formRetour" => $this->getFormDeconnexion(),
                    "formResto" => $this->getFormResto(),
                    "utilisateur" => $_SESSION['pseudo'] ?? "aucun",
                    "email" => $_SESSION['email'],
                    "nom" => $_SESSION['nom'],
                    "prenom" => $_SESSION['prenom'],
                    "mdp" => $_SESSION['mdp'],
                    "bestrestaurants" => $bestrestaurants,
                    
                   
            ]);
        }
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

   

    public function getFormRegister()
    {   
        $form = new Form("/?controller=ControlleurHome&action=view", Form::GET, "home_form");
        $form->setController("ControlleurHome", "submit");
        $form->addInput(new Link("/?controller=ControlleurRegister&action=view", "Register"));
        return $form;
    }

    public function getFormResto()
    {   
        $form = new Form("/?controller=ControlleurHome&action=view", Form::GET, "home_form");
        $form->setController("ControlleurHome", "submit");
        $form->addInput(new Link("/?controller=ControlleurResto&action=view", "Les Restos"));
        return $form;
    }

    public function map()
    {
        $dbRestaurant = new DBRestaurant();
        $restaurants = $dbRestaurant->getAllRestaurant();

        $this->render("map.php", [
            "restaurants" => $restaurants,
        ]);
    }
}
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
        $form->addInput(new Text("", true, "recherche", "recherche", "filtrages()", "", "oninput"));
        return $form;
    }

}
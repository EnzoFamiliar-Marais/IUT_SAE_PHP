<?php
namespace Controlleur;

use Auth\DBRestaurant;
use Auth\DBCritique;
use Auth\DBAuth;
use form\Form;
use form\type\Link;
use form\type\Select;
use form\type\Submit;
use form\type\Hidden;
use form\type\Text;

class ControlleurCritique extends Controlleur
{
   
    public function view()
    {
        
            //$restaurants = DBRestaurant::getAllRestaurant();
            //$critiques = DBCritique::getAllCritiques();
                

          
                $this->render("critique.php", [
                    //"restaurants" => $restaurants,
                    //"critiques" => $critiques,
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
    
  

}
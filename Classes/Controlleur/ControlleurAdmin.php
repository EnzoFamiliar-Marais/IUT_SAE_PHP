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

class ControlleurAdmin extends Controlleur
{
   
    public function view()
    {
        
            //$restaurants = DBRestaurant::getAllRestaurant();
            //$critiques = DBCritique::getAllCritiques();
                

          
                $this->render("admin.php", [
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
        $forms = new Form("/?controller=ControlleurAdmin&action=submitDelete", Form::POST, "admin_form");
        $forms->setController("ControlleurAdmin", "submitDelete");
        $forms->addInput(new Hidden($id,true, "user_id", "user_id")); 
        $forms->addInput(new Submit("Supprimer", true, "", "", ""));
        
        return $forms;
        }
    
        public function getFormLink($idUser){
            $form = new Form("/?controller=ControlleurAdmin&action=view", Form::GET, "get_form");
            $form->addInput(new Link("/?controller=ControlleurCritique&action=view&id={$idUser}", "Visualiser"));
            return $form;
        }
  

}
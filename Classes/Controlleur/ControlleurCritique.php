<?php
namespace Controlleur;

use Auth\DBRestaurant;
use Auth\DBCritique;
use Auth\DBAuth;
use form\Form;
use form\type\Link;
use form\type\Select;
use form\type\Submit;
use form\type\Text;

class ControlleurCritique extends Controlleur
{
   
    public function view()
    {
        
            $restaurants = DBRestaurant::getAllRestaurant();
            $critiques = DBCritique::getAllCritiques();
                

          
                $this->render("admin.php", [
                    "restaurants" => $restaurants,
                    "critiques" => $critiques,
                   
            ]);
        }
        

    public function submit()
    {
        $auth = new DBAuth();
        $auth->logout();
        $this->redirect("ControlleurLogin", "view");
    }

   

  

}
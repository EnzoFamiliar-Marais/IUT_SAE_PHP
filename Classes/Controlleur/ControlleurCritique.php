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
        $idUser = $_GET["id"];
        $utilisateur = DBAuth::getUserById($idUser);
        $critiques = DBCritique::getCritiqueByUser($idUser);
        $restaurants = DBRestaurant::getAllRestaurant();
        $restaurantsFiltrer = array_filter($restaurants, function ($restaurant) use ($critiques) {
            foreach ($critiques as $critique) {
                if ($restaurant['id'] == $critique['idR']) {
                    return true;
                }
            }
            return false;
        });

        error_log("Le visiteur " . print_r($utilisateur, true));
        error_log("Les Critiques " . print_r($critiques, true));
        error_log("Les Restaurants Filtrer " . print_r($restaurantsFiltrer, true));
        
        $this->render("admincritique.php", [
            "restaurants" => $restaurantsFiltrer,
            "critiques" => $critiques,
            "utilisateur" => $utilisateur,
            "formDeconnexion" => $this->getFormDeconnexion(),
        ]);
    }

    public function edit()
    {
        if (!isset($_SESSION['auth'])) {
            $this->redirect("ControlleurLogin", "view");
        }

        $critiqueId = $_GET['id'];
        $critique = DBCritique::getCritiqueById($critiqueId);
        $restaurants = DBRestaurant::getAllRestaurant();

        $this->render("edit_critique.php", [
            "critique" => $critique,
            "restaurants" => $restaurants,
            "formDeconnexion" => $this->getFormDeconnexion(),
        ]);
    }

    public function delete()
    {
        if (!isset($_SESSION['auth'])) {
            $this->redirect("ControlleurLogin", "view");
        }

        $critiqueId = $_POST['id'];

        $dbCritique = new DBCritique();
        $dbCritique->deleteCritique($critiqueId);

        $this->redirect("ControlleurCompte", "gererAvis");
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

    public function postFormDeleteAdmin($id)
    {
        $forms = new Form("/?controller=ControlleurCritique&action=submitDelete", Form::POST, "admin_form");
        $forms->setController("ControlleurCritique", "submitDelete");
        $forms->addInput(new Hidden($id, true, "critique_id", "critique_id"));
        $forms->addInput(new Submit("Supprimer", true, "", "", ""));
        return $forms;
    }

    public function submitDeleteCritique()
    {
        if (!isset($_SESSION['auth'])) {
            $this->redirect("ControlleurLogin", "view");
        }

        $critiqueId = $_POST['critique_id']; 

        $dbCritique = new DBCritique();
        $dbCritique->deleteCritique($critiqueId);
        $this->redirect("ControlleurAdmin", "view");
    }
}
?>

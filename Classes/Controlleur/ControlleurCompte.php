<?php

namespace Controlleur;

use Auth\DBAuth;
use Auth\DBCritique;
use Auth\DBRestaurant;
use form\Form;
use form\type\Submit;


class ControlleurCompte extends Controlleur
{
    public function view()
    {
        if (!isset($_SESSION['auth'])) {
            $this->redirect("ControlleurLogin", "view");
        }

        $userId = $_SESSION['auth'];
        $user = DBAuth::getUserById($userId);
        $critiques = DBCritique::getCritiqueByUser($userId);
        $favoris = DBRestaurant::getFavorisByUser($userId);

        $this->render("compte.php", [
            "user" => $user,
            "critiques" => $critiques,
            "favoris" => $favoris,
            "formDeconnexion" => $this->getFormDeconnexion(),
        ]);
    }

    public function getFormDeconnexion()
    {
        $form = new Form("/?controller=ControlleurCompte&action=logout", Form::GET, "logout_form");
        $form->addInput(new Submit("Deconnexion", true, "", ""));
        return $form;
    }

    public function logout()
    {
        $auth = new DBAuth();
        $auth->logout();
        $this->redirect("ControlleurHome", "view");
    }
}
?>
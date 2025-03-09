<?php

namespace Controlleur;

use Auth\DBAuth;
use Auth\DBCritique;
use Auth\DBFavoris;
use form\Form;
use form\type\Submit;

class ControlleurCompte extends Controlleur
{
    public function view()
    {
        if (!isset($_SESSION['auth'])) {
            $this->redirect("ControlleurLogin", "view");
            return;
        }

        $userId = $_SESSION['auth'];
        $dbAuth = new DBAuth();
        $dbCritique = new DBCritique();
        
        $user = $dbAuth->getUserById($userId);
        if (!$user) {
            error_log("User not found: $userId");
            $this->redirect("ControlleurLogin", "view");
            return;
        }
        
        $critiques = $dbCritique->getCritiqueByUser($userId);

        $this->render("compte.php", [
            "user" => $user,
            "critiques" => $critiques,
            "formDeconnexion" => $this->getFormDeconnexion(),
        ]);
    }

    public function update()
    {
        if (!isset($_SESSION['auth'])) {
            $this->redirect("ControlleurLogin", "view");
            return;
        }

        $userId = $_SESSION['auth'];
        $nom = $_POST['nom'] ?? '';
    
        $prenom = $_POST['prenom'] ?? '';
        $email = $_POST['email'] ?? '';
        $pseudo = $_POST['pseudo'] ?? '';
        $dbAuth = new DBAuth();
        $dbAuth->updateUser($userId, $pseudo, $nom, $prenom, $email);

        $this->redirect("ControlleurCompte", "view");
    }

    public function gererAvis()
    {
        if (!isset($_SESSION['auth'])) {
            $this->redirect("ControlleurLogin", "view");
            return;
        }

        $userId = $_SESSION['auth'];
        $dbCritique = new DBCritique();
        $critiques = $dbCritique->getCritiqueByUser($userId);

        $this->render("gerer_avis.php", [
            "critiques" => $critiques,
            "formDeconnexion" => $this->getFormDeconnexion(),
        ]);
    }

    public function gererFavoris()
    {
        if (!isset($_SESSION['auth'])) {
            $this->redirect("ControlleurLogin", "view");
            return;
        }

        $userId = $_SESSION['auth'];
        $dbFavoris = new DBFavoris();
        $favoris = $dbFavoris->getFavorisWithRestaurantNames($userId);

        $this->render("gerer_favoris.php", [
            "favoris" => $favoris,
            "formDeconnexion" => $this->getFormDeconnexion(),
        ]);
    }

    public function deleteFavoris()
    {
        if (!isset($_SESSION['auth'])) {
            $this->redirect("ControlleurLogin", "view");
            return;
        }

        $favorisId = $_POST['id'];
        $dbFavoris = new DBFavoris();
        $dbFavoris->deleteFavoris($favorisId);

        $this->redirect("ControlleurCompte", "gererFavoris");
    }

    public function getFormDeconnexion()
    {
        $form = new Form("/?controller=ControlleurCompte&action=logout", Form::GET, "logout_form");
        $form->setController("ControlleurCompte", "logout");
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
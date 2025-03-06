<?php

namespace Controlleur;

use Auth\DBAuth;
use Auth\DBCritique;
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
        }

        $userId = $_SESSION['auth'];
        $pseudo = $_POST['pseudo'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];

        $dbAuth = new DBAuth();
        $dbAuth->updateUser($userId, $pseudo, $nom, $prenom, $email);

        $this->redirect("ControlleurCompte", "view");
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
?>
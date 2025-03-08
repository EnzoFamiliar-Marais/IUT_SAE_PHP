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
        $utilisateurs = DBAuth::getUserByRole(2); // 2 = visiteur enregistré
        $critiques = array();
        foreach ($utilisateurs as $utilisateur) {
            $id = $utilisateur['id'];
            $userCritiques = DBCritique::getCritiqueByUser($id);
            $critiques = array_merge($critiques, $userCritiques);
        }

        error_log("Les Critiques " . print_r($critiques, true));
        error_log("Les Utilisateurs " . print_r($utilisateurs, true));

        if (!isset($_SESSION['id_role'])) {
            $this->redirect("ControlleurLogin", "view");
        } elseif (isset($_SESSION['id_role']) && $_SESSION['id_role'] == 1) {
            $this->render("admin.php", [
                "critiques" => $critiques,
                "utilisateurs" => $utilisateurs,
                "formDeconnexion" => $this->getFormDeconnexion(),
            ]);
        } else {
            $this->redirect("ControlleurLogin", "view");
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

    public function getFormDeleteAdmin($id)
    {
        $form = new Form("/?controller=ControlleurAdmin&action=submitDelete", Form::POST, "admin_form");
        $form->setController("ControlleurAdmin", "submitDelete");
        $form->addInput(new Hidden($id, true, "user_id", "user_id"));
        $form->addInput(new Submit("Supprimer", true, "", "", ""));
        return $form;
    }

    public function getFormLink($idUser)
    {
        $form = new Form("/?controller=ControlleurAdmin&action=view", Form::GET, "get_form");
        $form->addInput(new Link("/?controller=ControlleurCritique&action=view&id={$idUser}", "Visualiser"));
        return $form;
    }

    public function submitDelete()
    {
        if (!isset($_SESSION['auth'])) {
            $this->redirect("ControlleurLogin", "view");
        }

        $idUser = $_POST['user_id'];

        $dbAuth = new DBAuth();
        $dbAuth->deleteUserFromDB($idUser);
        $this->redirect("ControlleurAdmin", "view");
    }
}
?>

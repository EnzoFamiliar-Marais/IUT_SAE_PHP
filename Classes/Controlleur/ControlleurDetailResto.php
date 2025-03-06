<?php
namespace Controlleur;

use Auth\DBRestaurant;
use Auth\DBCritique;
use Auth\DBAuth;
use Auth\DBCommune;
use Auth\DBDepartement;
use Auth\DBRegion;
use form\Form;
use form\type\Link;
use form\type\Select;
use form\type\Submit;
use form\type\Hidden;
use form\type\Text;

class ControlleurDetailResto extends Controlleur
{
    public function view()
    {
        if (!isset($_GET['id'])) {
            $this->redirect("ControlleurResto", "view");
        }

        $restaurantId = $_GET['id'];
        $dbRestaurant = new DBRestaurant();
        $restaurant = $dbRestaurant->getRestaurantById($restaurantId);

        $dbCritique = new DBCritique();
        $critiques = $dbCritique->getCritiqueByRestaurant($restaurantId);

        $this->render("details_resto.php", [
            "restaurant" => $restaurant,
            "critiques" => $critiques,
            "formDeconnexion" => $this->getFormDeconnexion(),
        ]);
    }

    public function submit()
    {
        $auth = new DBAuth();
        $auth->logout();
        $this->redirect("ControlleurDetailResto", "view");
    }

    public function submitAvis()
    {
        $dbCritique = new DBCritique();
        $dbRestaurant = new DBRestaurant();
        $userId = $_SESSION['auth'];
        $restaurantId = $_GET['id'];
        $note = $_POST['note'];
        $commentaire = $_POST['commentaire'];

        $dbCritique->addCritique($userId, $restaurantId, $note, $commentaire);
        $this->redirect("ControlleurDetailResto", "view", $restaurantId);
    }

    public function getFormDeconnexion()
    {
        $form = new Form("/?controller=ControlleurDetailResto&action=submit", Form::GET, "home_form");
        $form->setController("ControlleurDetailResto", "submit");
        $form->addInput(new Submit("Deconnexion", true, "", ""));
        return $form;
    }
}
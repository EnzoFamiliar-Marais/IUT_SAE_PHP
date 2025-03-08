<?php

namespace Controlleur;

use Auth\DBFavoris;
use Auth\DBRestaurant;
use form\Form;
use form\type\Submit;

class ControlleurFavoris extends Controlleur
{
    public function view()
    {
        if (!isset($_SESSION['auth'])) {
            $this->redirect("ControlleurLogin", "view");
        }

        $userId = $_SESSION['auth'];
        $favoris = (new DBFavoris())->getFavorisByUser($userId);
        $restaurants = DBRestaurant::getAllRestaurant();

        $this->render("favoris.php", [
            "favoris" => $favoris,
            "restaurants" => $restaurants,
            "formDeconnexion" => $this->getFormDeconnexion(),
        ]);
    }

    public function gererFavoris()
    {
        if (!isset($_SESSION['auth'])) {
            $this->redirect("ControlleurLogin", "view");
        }

        $userId = $_SESSION['auth'];
        $dbFavoris = new DBFavoris();
        $favoris = $dbFavoris->getFavorisWithRestaurantNames($userId);

        $this->render("gerer_favoris.php", [
            "favoris" => $favoris,
            "formDeconnexion" => $this->getFormDeconnexion(),
        ]);
    }

    public function add()
    {
        if (!isset($_SESSION['auth'])) {
            $this->redirect("ControlleurLogin", "view");
        }

        $userId = $_SESSION['auth'];
        $restaurantId = $_POST['restaurant_id'];

        $dbFavoris = new DBFavoris();
        $dbFavoris->addFavoris($userId, $restaurantId);

        $this->redirect("ControlleurFavoris", "view");
    }

    public function delete()
    {
        if (!isset($_SESSION['auth'])) {
            $this->redirect("ControlleurLogin", "view");
        }

        $favorisId = $_POST['id'];

        $dbFavoris = new DBFavoris();
        $dbFavoris->deleteFavoris($favorisId);

        $this->redirect("ControlleurFavoris", "gererFavoris");
    }

    public function getFormDeconnexion()
    {
        $form = new Form("/?controller=ControlleurFavoris&action=submit", Form::GET, "logout_form");
        $form->setController("ControlleurFavoris", "submit");
        $form->addInput(new Submit("Deconnexion", true, "", ""));
        return $form;
    }
}
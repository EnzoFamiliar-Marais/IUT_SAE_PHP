<?php

namespace Controlleur;
use Auth\DBAuth;
use Auth\DBPlaylist;
use form\Form;
use form\type\Submit;
use form\type\Text;
use form\type\PasswordField;
use form\type\MailField;
use form\type\Link;


class ControlleurRegister extends Controlleur{

    public function view()
    {
            $this->render("register.php", ["form" => $this->getForm()]);
    }

    public function submit()
    {
        $password = $_POST['password'];
        $email = $_POST['email'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $pseudo = $_POST['pseudo'];
        if(empty(trim($pseudo))){
            $pseudo = "Utilisateur";
        }
        $auth = new DBAuth;
        $user = $auth->addUser($password, $email, $nom, $prenom, date("Y-m-d"), 2, $pseudo); // 2 = id_role utilisateur
        $_SESSION["userRegister"] = $user;
        if($user){
            $auth->login($email, $password);
            $this->redirect("ControlleurHome", "view");
        }else{
            $this->redirect("ControlleurRegister", "view");
        }
    }

    private function getForm()
    {
        $form = new Form("/?controller=ControlleurRegister&action=submit", Form::POST, "register_form");
        $form->addInput((new Text("", true,"pseudo", "pseudo"))->setLabel("Nom d'utilisateur"));
        $form->addInput((new Text("", true,"prenom", "prenom"))->setLabel("Prenom"));
        $form->addInput((new Text("", true,"nom", "nom"))->setLabel("Nom"));
        $form->addInput((new MailField("", true,"email", "email"))->setLabel("Email"));
        $passwordField = new PasswordField("", true, "password", "password");
        $passwordField->setLabel("Mot de passe");
        $form->addInput($passwordField);
        $form->setController("ControlleurRegister", "submit");
        $form->addInput(new Submit("Inscription", true, "inscription", "inscriptionId"));
        return $form;
    }
}
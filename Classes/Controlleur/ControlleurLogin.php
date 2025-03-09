<?php
namespace Controlleur;

use Auth\DBAuth;
use form\Form;
use form\type\Link;
use form\type\Submit;
use form\type\Text;
use form\type\PasswordField;

class ControlleurLogin extends Controlleur
{
    private $auth;

    public function __construct()
    {
        $this->auth = new DBAuth();
    }
    
    public function view()
    {   
        // Si l'utilisateur est déjà connecté, on le redirige
        if(isset($_SESSION['auth'])) {
            if($_SESSION['id_role'] === 1) {
                $this->redirect("ControlleurAdmin", "view");
            } else {
                $this->redirect("ControlleurHome", "view");
            }
            return;
        }

        $this->render("login.php", [
            "form" => $this->getForm(),
            "error" => $_SESSION['errorConnexion'] ?? null
        ]);

        // Nettoyer le message d'erreur après l'avoir affiché
        if(isset($_SESSION['errorConnexion'])) {
            unset($_SESSION['errorConnexion']);
        }
    }

    public function submit()
    {   
        if(!isset($_POST['email']) || !isset($_POST['password'])) {
            $_SESSION['errorConnexion'] = "Veuillez remplir tous les champs";
            $this->redirect("ControlleurLogin", "view");
            return;
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->auth->login($email, $password);
        if($user) {
           
            $redirect_url = isset($_SESSION['previous_page']) ? $_SESSION['previous_page'] : '/?controller=ControlleurHome&action=view';
            unset($_SESSION['previous_page']); 
            header('Location: ' . $redirect_url);
            exit();
        } else {
            $_SESSION['errorConnexion'] = "Email ou mot de passe incorrect";
            $this->redirect("ControlleurLogin", "view");
        }
    }

    private function getForm()
    {
        $form = new Form("/?controller=ControlleurLogin&action=submit", Form::POST, "login_form");
        
        $emailField = new Text("", true, "email", "email");
        $emailField->setLabel("Email");
        $form->addInput($emailField);
        
        $passwordField = new PasswordField("", true, "password", "password");
        $passwordField->setLabel("Mot de passe");
        $form->addInput($passwordField);
        
        $form->addInput(new Submit("Se connecter", true, "connexion", "connexionId"));

        return $form;
    }
}
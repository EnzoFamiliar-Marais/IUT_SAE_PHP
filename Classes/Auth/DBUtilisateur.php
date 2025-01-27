<?php

namespace Auth;

use data\Database;
use PDO;
class DBAuth
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }


    public function getUserId()
    {
        if ($this->logged()) {
            return $_SESSION['auth'];
        }
        return false;
    }

    public function login($email, $password)
{
    $stmt = $this->db->prepare('SELECT * FROM UTILISATEUR WHERE email = ?', [$email]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);
    
    if($user){
        if($user->mdp === $password){
            $_SESSION['auth'] = $user->idutilisateur;
            $_SESSION['nom'] = $user->nom;
            $_SESSION['email'] = $user->email;
            $_SESSION['prenom'] = $user->prenom;
            $_SESSION['mdp'] = $user->mdp;
            $_SESSION['id_role'] = $user->id_role;
            $_SESSION['date_creation'] = $user->date_create;
            return $user;
        }
    }
    
    return false;
}

public function addUser($username, $password, $email, $nom, $prenom, $dateCreation)
{
    $stmt = $this->db->prepare("SELECT * FROM UTILISATEUR WHERE email = ?", [$email]);
    $existingUser = $stmt->fetch(PDO::FETCH_OBJ);
    
    if ($existingUser) {
        $_SESSION['errorAdd'] = "Email déjà utilisé";
        return false; 
    }
    
    $stmt = $this->db->prepare("INSERT INTO UTILISATEUR (id, nom, prenom, email, mdp, date_creation, id_role) VALUES (?, ?, ?, ?, ?,?, ?)", [$username, $nom, $prenom, $email, $password, $dateCreation ,2]);

    return $stmt !== false;
}

    public function logged()
    {
        return isset($_SESSION['auth']);
    }

    public function logout()
    {
        session_destroy();
        
    }
}

?>

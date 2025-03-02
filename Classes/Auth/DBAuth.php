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
    $stmt = $this->db->prepare('SELECT * FROM "UTILISATEURS" WHERE email = ?', [$email]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);
    
    if($user){
        echo $user->mdp;
        echo $password;
        if($user->mdp === $password){
            $_SESSION['auth'] = $user->id;
            $_SESSION['nom'] = $user->nom;
            $_SESSION['email'] = $user->email;
            $_SESSION['prenom'] = $user->prenom;
            $_SESSION['mdp'] = $user->mdp;
            $_SESSION['id_role'] = $user->idRole;
            $_SESSION['date_creation'] = $user->date_creation;
            return $user;
        }
    }
    $_SESSION['errorLogin'] = "Email ou mot de passe incorrect";
    return false;
}

public function addUser($password, $email, $nom, $prenom, $dateCreation, $idRole)
{
    $stmt = $this->db->prepare('SELECT * FROM "UTILISATEURS" WHERE email = ?', [$email]);
    $existingUser = $stmt->fetch(PDO::FETCH_OBJ);
    
    if ($existingUser) {
        $_SESSION['errorAdd'] = "Email déjà utilisé";
        return false; 
    }
    
    $stmt = $this->db->prepare('INSERT INTO "UTILISATEURS" (nom, prenom, email, mdp, date_creation, "idRole") VALUES (?, ?, ?, ?, ?, ?)', [$nom, $prenom, $email, $password, $dateCreation, $idRole]);

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

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

        if ($user && (password_verify($password, $user->mdp) || $password === $user->mdp)) {
            $_SESSION['auth'] = $user->id;
            $_SESSION['pseudo'] = $user->pseudo;
            $_SESSION['nom'] = $user->nom;
            $_SESSION['email'] = $user->email;
            $_SESSION['prenom'] = $user->prenom;
            $_SESSION['mdp'] = $user->mdp;
            $_SESSION['id_role'] = $user->idRole;
            $_SESSION['date_creation'] = $user->date_creation;
            return $user;
        }

        $_SESSION['errorLogin'] = "Email ou mot de passe incorrect";
        return false;
    }

    public function addUser($password, $email, $nom, $prenom, $dateCreation, $idRole, $pseudo)
    {
        $stmt = $this->db->prepare('SELECT * FROM "UTILISATEURS" WHERE email = ?', [$email]);
        $existingUser = $stmt->fetch(PDO::FETCH_OBJ); 

        if ($existingUser) {
            $_SESSION['errorAdd'] = "Email déjà utilisé";
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare('INSERT INTO "UTILISATEURS" (nom, prenom, email, mdp, date_creation, "idRole", pseudo) VALUES (?, ?, ?, ?, ?, ?, ?)', [$nom, $prenom, $email, $hashedPassword, $dateCreation, $idRole, $pseudo]);

        return $stmt !== false;
    }

    public static function fetchAllUsers()
    {
        $db = new DBAuth();
        $stmt = $db->db->query('SELECT * FROM "UTILISATEURS"');
        return $stmt;
    }

    public static function getAllUsers(): array
    {
        $users = array();

        foreach (DBAuth::fetchAllUsers() as $user) {
            $users[] = [
                "id" => $user['id'],
                "nom" => $user['nom'],
                "prenom" => $user['prenom'],
                "email" => $user['email'],
                "mdp" => $user['mdp'],
                "date_creation" => $user['date_creation'],
                "id_role" => $user['idRole'],
                "pseudo" => $user['pseudo']
            ];
        }
        return $users;
    }

    public static function getUserByRole($idRole) : array
    {
        $users = self::getAllUsers();
        $usersByRole = array_filter($users, function($user) use ($idRole) {
            return $user['id_role'] == $idRole;
        });

        return array_values($usersByRole);
    }

    public static function getUserById($idUser) : array
    {
        $users = self::getAllUsers();
        foreach ($users as $user) {
            if ($user['id'] == $idUser) {
                return $user;
            }
        }
        return [];
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
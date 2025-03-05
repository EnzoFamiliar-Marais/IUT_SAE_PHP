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
            $_SESSION['email'] = $user->email;
            $_SESSION['nom'] = $user->nom;
            $_SESSION['prenom'] = $user->prenom;
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
            $_SESSION['errorAdd'] = "Utilisateur existe déjà";
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare('INSERT INTO "UTILISATEURS" (nom, prenom, email, mdp, date_creation, "idRole", pseudo) VALUES (?, ?, ?, ?, ?, ?, ?)', [$nom, $prenom, $email, $hashedPassword, $dateCreation, $idRole, $pseudo]);

        return $stmt !== false;
    }

    public function updateUser($userId, $pseudo, $nom, $prenom, $email)
    {
        $stmt = $this->db->prepare('UPDATE "UTILISATEURS" SET pseudo = ?, nom = ?, prenom = ?, email = ? WHERE id = ?', [$pseudo, $nom, $prenom, $email, $userId]);
        return $stmt->execute();
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
            $users[] = array(
                'id' => $user['id'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'email' => $user['email'],
                'pseudo' => $user['pseudo'],
                'date_creation' => $user['date_creation'],
                'idRole' => $user['idRole']
            );
        }
        return $users;
    }

    public static function getUserByRole($idRole) : array
    {
        $users = array();
        $db = new DBAuth();
        $stmt = $db->db->prepare('SELECT * FROM "UTILISATEURS" WHERE "idRole" = ?', [$idRole]);
        foreach ($stmt as $user) {
            $users[] = array(
                'id' => $user['id'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'email' => $user['email'],
                'pseudo' => $user['pseudo'],
                'date_creation' => $user['date_creation'],
                'idRole' => $user['idRole']
            );
        }
        return $users;
    }

    public static function getUserById($idUser) : array
    {
        $db = new DBAuth();
        $stmt = $db->db->prepare('SELECT * FROM "UTILISATEURS" WHERE id = ?', [$idUser]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user : [];
    }

    public function logged()
    {
        return isset($_SESSION['auth']);
    }

    public function logout()
    {
        session_unset();
        session_destroy();
    }
}
?>
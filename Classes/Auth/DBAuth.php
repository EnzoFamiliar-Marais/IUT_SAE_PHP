<?php

namespace Auth;

use data\Database;
use PDO;
use PDOException;

class DBAuth
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getPDO();
    }

    public function login($email, $password)
    {
        try {
            error_log("Attempting login for email: $email");

            $stmt = $this->db->prepare('SELECT * FROM "UTILISATEURS" WHERE email = :email');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['mdp'])) {
                $_SESSION['auth'] = $user['id'];
                return true;
            }

            error_log("Login failed for email: $email");
            return false;
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }

    public function addUser($password, $email, $nom, $prenom, $dateCreation, $idRole, $pseudo)
    {
        $stmt = $this->db->prepare('SELECT * FROM "UTILISATEURS" WHERE email = ?');
        $stmt->execute([$email]);
        $existingUser = $stmt->fetch(PDO::FETCH_OBJ);

        if ($existingUser) {
            $_SESSION['errorAdd'] = "Utilisateur existe déjà";
            return false;
        }

        $stmt = $this->db->prepare('INSERT INTO "UTILISATEURS" (email, mdp, nom, prenom, date_creation, "idRole", pseudo) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->execute([$email, $hashedPassword, $nom, $prenom, $dateCreation, $idRole, $pseudo]);

        return true;
    }

    public function logout()
    {
        unset($_SESSION['auth']);
        session_destroy();
    }

    public function isLogged()
    {
        return isset($_SESSION['auth']);
    }

    public function getUserById($id)
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM "UTILISATEURS" WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user;
        } catch (PDOException $e) {
            error_log("Error getting user by ID: " . $e->getMessage());
            return null;
        }
    }

    public function updateUser($userId, $nom, $prenom, $email)
    {
        try {
            $stmt = $this->db->prepare('
                UPDATE "UTILISATEURS" 
                SET nom = :nom, 
                    prenom = :prenom, 
                    email = :email 
                WHERE id = :id
            ');

            $success = $stmt->execute([
                'id' => $userId,
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email
            ]);

            return $success;
        } catch (PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }

    public function deleteUserFromDB($id)
    {
        $stmt = $this->db->prepare('DELETE FROM "UTILISATEURS" WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt !== false;
    }
}
?>

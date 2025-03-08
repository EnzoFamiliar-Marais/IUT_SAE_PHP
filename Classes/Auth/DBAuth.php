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
            $stmt = $this->db->prepare('SELECT * FROM "UTILISATEURS" WHERE email = :email');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['mdp'])) {
                // Ne stocker que les informations non sensibles en session
                $_SESSION['auth'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['pseudo'] = $user['pseudo'];
                $_SESSION['id_role'] = $user['id_role'];
                return $user;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }

    public function addUser($password, $email, $nom, $prenom, $dateCreation, $idRole, $pseudo)
    {
        try {
            // Vérifier si l'email existe déjà
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM "UTILISATEURS" WHERE email = :email');
            $stmt->execute(['email' => $email]);
            if ($stmt->fetchColumn() > 0) {
                $_SESSION['errorAdd'] = "Cette adresse email est déjà utilisée";
                return false;
            }

            // Vérifier si le pseudo existe déjà
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM "UTILISATEURS" WHERE pseudo = :pseudo');
            $stmt->execute(['pseudo' => $pseudo]);
            if ($stmt->fetchColumn() > 0) {
                $_SESSION['errorAdd'] = "Ce pseudo est déjà utilisé";
                return false;
            }

            // Hasher le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->db->prepare('
                INSERT INTO "UTILISATEURS" (mdp, email, nom, prenom, date_creation, id_role, pseudo) 
                VALUES (:mdp, :email, :nom, :prenom, :date_creation, :id_role, :pseudo)
            ');

            return $stmt->execute([
                'mdp' => $hashedPassword,
                'email' => $email,
                'nom' => $nom,
                'prenom' => $prenom,
                'date_creation' => $dateCreation,
                'id_role' => $idRole,
                'pseudo' => $pseudo
            ]);
        } catch (PDOException $e) {
            error_log("Error adding user: " . $e->getMessage());
            return false;
        }
    }

    public function logout()
    {
        // Détruire toutes les données de session
        $_SESSION = array();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-3600, '/');
        }
        session_destroy();
    }

    public function isLogged()
    {
        return isset($_SESSION['auth']) && $_SESSION['auth'] === true;
    }

    public function getUserById($id)
    {
        try {
            $stmt = $this->db->prepare('
                SELECT id, email, nom, prenom, pseudo, id_role, date_creation 
                FROM "UTILISATEURS" 
                WHERE id = :id
            ');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting user by ID: " . $e->getMessage());
            return null;
        }
    }

    public function updateUser($userId, $nom, $prenom, $email)
    {
        try {
            // Vérifier si l'email existe déjà pour un autre utilisateur
            $stmt = $this->db->prepare('
                SELECT COUNT(*) 
                FROM "UTILISATEURS" 
                WHERE email = :email AND id != :id
            ');
            $stmt->execute(['email' => $email, 'id' => $userId]);
            if ($stmt->fetchColumn() > 0) {
                $_SESSION['errorUpdate'] = "Cette adresse email est déjà utilisée";
                return false;
            }

            $stmt = $this->db->prepare('
                UPDATE "UTILISATEURS" 
                SET nom = :nom, 
                    prenom = :prenom, 
                    email = :email,
                    date_modification = CURRENT_TIMESTAMP
                WHERE id = :id
            ');
            
            return $stmt->execute([
                'id' => $userId,
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email
            ]);
        } catch (PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }

    public function deleteUserFromDB($id)
    {
        try {
            // Supprimer d'abord les critiques de l'utilisateur
            $stmt = $this->db->prepare('DELETE FROM "Critiquer" WHERE "idU" = :id');
            $stmt->execute(['id' => $id]);

            // Puis supprimer l'utilisateur
            $stmt = $this->db->prepare('DELETE FROM "UTILISATEURS" WHERE id = :id');
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }
}

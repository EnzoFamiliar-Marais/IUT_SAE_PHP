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
            
            // Vérifier si l'utilisateur existe
            $stmt = $this->db->prepare('SELECT * FROM "UTILISATEURS" WHERE email = :email');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            error_log("User found: " . ($user ? "yes" : "no"));
            if ($user) {
                error_log("Stored password hash: " . $user['mdp']);
                error_log("Input password: " . $password);
            }

            if ($user && $user['mdp'] === $password) {
                error_log("Login successful for user ID: " . $user['id']);
                $_SESSION['auth'] = $user['id'];
                return true;
            }
            
            error_log("Login failed for email: $email");
            return false;
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());

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
            $_SESSION['errorAdd'] = "Utilisateur existe déjà";

            return false;
        }
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
            
            if ($user) {
                error_log("User found: ID=$id");
            } else {
                error_log("User not found: ID=$id");
            }
            
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

            if ($success) {
                error_log("Updated user: ID=$userId");
            } else {
                error_log("Failed to update user: ID=$userId");
            }

            return $success;
        } catch (PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }

}

    
    public function deleteUserFromDB($id)
    {
        $stmt = $this->db->prepare('DELETE FROM "UTILISATEURS" WHERE id = ?', [$id]);
        $stmt->execute();
        return $stmt !== false;
    }
}
?>

<?php

namespace Auth;

use data\Database;
use PDO;
use PDOException;

class DBCritique
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getPDO();
    }

    public function getAllCritiques()
    {
        try {
            $stmt = $this->db->query('SELECT * FROM "Critiquer"');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllCritiques: " . $e->getMessage());
            return [];
        }
    }

    public function addCritique($idUtilisateur, $idRestaurant, $note, $commentaire)
    
        try {
            $stmt = $this->db->prepare('
                INSERT INTO "Critiquer" ("idU", "idR", "note", "commentaire") 
                VALUES (:idU, :idR, :note, :commentaire)
            ');
        $stmt = $this->db->prepare(
            'INSERT INTO "Critiquer" ("idU","idR", "note", "commentaire") 
            VALUES (?, ?, ?, ?)',
            [
                $idUtilisateur, $idRestaurant, $note, $commentaire
            ]
        );
        
        return $stmt !== false;
    }

            $success = $stmt->execute([
                'idU' => $idUtilisateur,
                'idR' => $idRestaurant,
                'note' => $note,
                'commentaire' => $commentaire
            ]);

            if ($success) {
                error_log("Added critique: User=$idUtilisateur, Restaurant=$idRestaurant");
            } else {
                error_log("Failed to add critique: User=$idUtilisateur, Restaurant=$idRestaurant");
            }

            return $success;
        } catch (PDOException $e) {
            error_log("Error in addCritique: " . $e->getMessage());
            return false;
        }
    }

    public function getCritiqueByUser($idUtilisateur)
    {
        try {
            $stmt = $this->db->prepare('
                SELECT c.*, r.nom as restaurant_nom 
                FROM "Critiquer" c 
                JOIN "RESTAURANTS" r ON c."idR" = r.id 
                WHERE c."idU" = :idU
                ORDER BY c.date_critique DESC
            ');

            $stmt->execute(['idU' => $idUtilisateur]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getCritiqueByUser: " . $e->getMessage());
            return [];
        }
    }

    public function getCritiqueById($id)

    public function deleteCritique($id)
    {
        $stmt = $this->db->prepare('DELETE FROM "Critiquer" WHERE "id" = ?', [$id]);
        return $stmt->execute(); 
    }

    
    


    public static function getCritiqueById($id)
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM "Critiquer" WHERE "id" = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getCritiqueById: " . $e->getMessage());
            return null;
        }
    }

    public function getCritiqueByRestaurant($restaurantId)
    {
        try {
            $stmt = $this->db->prepare('
                SELECT c.*, u.pseudo 
                FROM "Critiquer" c 
                JOIN "UTILISATEURS" u ON c."idU" = u.id 
                WHERE c."idR" = :idR
                ORDER BY c.date_critique DESC
            ');

            $stmt->execute(['idR' => $restaurantId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getCritiqueByRestaurant: " . $e->getMessage());
            return [];
        }
    }

    public function deleteCritique($id)
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM "Critiquer" WHERE "id" = :id');
            $success = $stmt->execute(['id' => $id]);

            if ($success) {
                error_log("Deleted critique: ID=$id");
            } else {
                error_log("Failed to delete critique: ID=$id");
            }

            return $success;
        } catch (PDOException $e) {
            error_log("Error in deleteCritique: " . $e->getMessage());
            return false;
        }
    }
}
}


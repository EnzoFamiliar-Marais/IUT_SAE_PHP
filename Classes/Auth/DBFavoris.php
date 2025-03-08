<?php

namespace Auth;

use data\Database;
use PDO;
use PDOException;

class DBFavoris {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getPDO();
        $this->createTableIfNotExists();
    }

    private function createTableIfNotExists() {
        try {
            // Vérifier si la table existe déjà
            $stmt = $this->db->query("
                SELECT EXISTS (
                    SELECT FROM information_schema.tables 
                    WHERE table_name = 'favoris'
                )
            ");
            $exists = $stmt->fetchColumn();

            if (!$exists) {
                // Créer la table favoris avec une contrainte unique sur (id_utilisateur, id_restaurant)
                $this->db->exec("
                    CREATE TABLE favoris (
                        id SERIAL PRIMARY KEY,
                        id_utilisateur VARCHAR(255) NOT NULL,
                        id_restaurant INTEGER NOT NULL,
                        date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        CONSTRAINT unique_favoris UNIQUE (id_utilisateur, id_restaurant)
                    )
                ");
                error_log("Favoris table created successfully");
            }
        } catch (PDOException $e) {
            error_log("Error checking/creating favoris table: " . $e->getMessage());
        }
    }

    public function addFavoris($userId, $restaurantId) {
        try {
            // Convertir l'ID du restaurant en entier
            $restaurantId = (int)$restaurantId;

            // Vérifier si le favori existe déjà
            if ($this->isFavoris($userId, $restaurantId)) {
                error_log("Favorite already exists: User=$userId, Restaurant=$restaurantId");
                return true;
            }

            $stmt = $this->db->prepare("
                INSERT INTO favoris (id_utilisateur, id_restaurant)
                VALUES (:userId, :restaurantId)
            ");

            $success = $stmt->execute([
                'userId' => $userId,
                'restaurantId' => $restaurantId
            ]);

            if ($success) {
                error_log("Added favorite: User=$userId, Restaurant=$restaurantId");
            } else {
                error_log("Failed to add favorite: User=$userId, Restaurant=$restaurantId");
            }

            return $success;
        } catch (PDOException $e) {
            error_log("Error in addFavoris: " . $e->getMessage());
            return false;
        }
    }

    public function removeFavoris($userId, $restaurantId) {
        try {
            // Convertir l'ID du restaurant en entier
            $restaurantId = (int)$restaurantId;

            $stmt = $this->db->prepare("
                DELETE FROM favoris
                WHERE id_utilisateur = :userId
                AND id_restaurant = :restaurantId
            ");

            $success = $stmt->execute([
                'userId' => $userId,
                'restaurantId' => $restaurantId
            ]);

            if ($success) {
                error_log("Removed favorite: User=$userId, Restaurant=$restaurantId");
            } else {
                error_log("Failed to remove favorite: User=$userId, Restaurant=$restaurantId");
            }

            return $success;
        } catch (PDOException $e) {
            error_log("Error in removeFavoris: " . $e->getMessage());
            return false;
        }
    }

    public function isFavoris($userId, $restaurantId) {
        try {
            // Convertir l'ID du restaurant en entier
            $restaurantId = (int)$restaurantId;

            $stmt = $this->db->prepare("
                SELECT EXISTS (
                    SELECT 1
                    FROM favoris
                    WHERE id_utilisateur = :userId
                    AND id_restaurant = :restaurantId
                ) as exists
            ");

            $stmt->execute([
                'userId' => $userId,
                'restaurantId' => $restaurantId
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $isFavoris = $result['exists'] === 't' || $result['exists'] === true;
            
            error_log("Checking if favorite exists: User=$userId, Restaurant=$restaurantId, Result=" . ($isFavoris ? "yes" : "no"));
            
            return $isFavoris;
        } catch (PDOException $e) {
            error_log("Error in isFavoris: " . $e->getMessage());
            return false;
        }
    }

    public function getFavoris($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT id_restaurant
                FROM favoris
                WHERE id_utilisateur = :userId
                ORDER BY date_ajout DESC
            ");

            $stmt->execute(['userId' => $userId]);
            $favoris = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            error_log("Retrieved favorites for User=$userId: " . implode(", ", $favoris));
            
            return $favoris;
        } catch (PDOException $e) {
            error_log("Error in getFavoris: " . $e->getMessage());
            return [];
        }
    }

    public function getFavorisWithRestaurantNames($userId) {
        try {
            $stmt = $this->db->prepare('
                SELECT f.id, f.id_restaurant, r.nom as restaurant
                FROM favoris f
                JOIN "Restaurants" r ON f.id_restaurant = r.id
                WHERE f.id_utilisateur = :userId
                ORDER BY f.date_ajout DESC
            ');

            $stmt->execute(['userId' => $userId]);
            $favoris = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Retrieved favorites with restaurant names for User=$userId: " . print_r($favoris, true));
            
            return $favoris;
        } catch (PDOException $e) {
            error_log("Error in getFavorisWithRestaurantNames: " . $e->getMessage());
            return [];
        }
    }

    public function deleteFavoris($favorisId) {
        try {
            $stmt = $this->db->prepare("
                DELETE FROM favoris
                WHERE id = :id
            ");

            $success = $stmt->execute(['id' => $favorisId]);

            if ($success) {
                error_log("Deleted favorite with ID: $favorisId");
            } else {
                error_log("Failed to delete favorite with ID: $favorisId");
            }

            return $success;
        } catch (PDOException $e) {
            error_log("Error in deleteFavoris: " . $e->getMessage());
            return false;
        }
    }
}

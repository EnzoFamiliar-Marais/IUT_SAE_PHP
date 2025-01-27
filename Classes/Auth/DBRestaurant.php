<?php

namespace Auth;

use data\Database;
use PDO;
use PDOException;

class DBRestaurant
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function fetchRestaurant()
    {
        $stmt = $this->db->query('SELECT * FROM "Restaurant"');
        return $stmt;
    }

    public function getAllRestaurant()
    {
        $restaurants = array();
        foreach ($this->fetchRestaurant() as $restaurant) {
            $restaurants[] = array(
                'id' => $restaurant['id'],
                'nom' => $restaurant['nom_Resto'],
                // Ajoutez d'autres colonnes si nécessaire
            );
        }
        return $restaurants;
    }

                                                                                                                                                                                                                   
    public function addRestaurant($nom_Resto, $adresse, $telephone, $photos, $siret, $opening_hours, $internet_access, $wheelchair, $type, $longitude, $latitude, $brand, $capacity, $stars, $website, $map, $operator, $vegetarian, $vegan, $delivery, $takeaway, $drive_through, $wikidata, $brand_wikidata, $facebook, $smoking, $idCommune) 
    {
        $selectStmt = $this->db->prepare('SELECT * FROM "Restaurant" WHERE nom = ?', [$nom_Resto]);
        $existingRestaurant = $selectStmt->fetch(PDO::FETCH_OBJ);
    
        if ($existingRestaurant) {
            $_SESSION['errorAdd'] = "Restaurant existe déjà";
            return false;
        }
        
        $stmt = $this->db->prepare(
            'INSERT INTO "Restaurants" (nom, adresse, phone, photo, siret, opening_hours, internet_access, wheelchair, "typeR", longitude, latitude, brand, capacity, stars, website, map, operator, vegetarian, vegan, delivery, takeaway, drive_through, wikidata, brand_wikidata, facebook, smoking, "idC") 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $nom_Resto, $adresse, $telephone, $photos, $siret, $opening_hours, $internet_access, $wheelchair, 
                $type, $longitude, $latitude, $brand, $capacity, $stars, $website, $map, $operator, $vegetarian, $vegan, 
                $delivery, $takeaway, $drive_through, $wikidata, $brand_wikidata, $facebook, $smoking, $idCommune
            ]
        );
    
        
        return $stmt !== false;
    }

    public function getTableNames()
    {
        $stmt = $this->db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function addTest(bool $testValue) {
        if (!is_bool($testValue)) {
            echo "Erreur : la valeur n'est pas un boolean valide.\n";
            return false;
        }
    
        $testValueStr = $testValue ? 'true' : 'false';
    
        $stmt = $this->db->getPDO()->prepare("INSERT INTO test (test) VALUES (?)");
        $stmt->execute([$testValueStr]);
    
        print("Test ajouté avec " . ($testValue ? "true" : "false") . "\n");
    
        return $stmt !== false;
    }   
}
?>
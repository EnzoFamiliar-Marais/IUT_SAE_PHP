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

    public static function fetchRestaurant()
    {
        $db = new DBRestaurant();
        $stmt = $db->db->query('SELECT * FROM "Restaurants"');
        return $stmt;
    }

    public static function getAllRestaurant()
    {
        $restaurants = array();
        foreach (DBRestaurant::fetchRestaurant() as $restaurant) {
            $restaurants[] = array(
                'id' => $restaurant['id'],
                'nom' => $restaurant['nom'],
                'adresse' => $restaurant['adresse'],
                'telephone' => $restaurant['phone'],
                'photos' => $restaurant['photo'],
                'siret' => $restaurant['siret'],
                'opening_hours' => $restaurant['opening_hours'],
                'internet_access' => $restaurant['internet_access'],
                'wheelchair' => $restaurant['wheelchair'],
                'type' => $restaurant['typeR'],
                'longitude' => $restaurant['longitude'],
                'latitude' => $restaurant['latitude'],
                'brand' => $restaurant['brand'],
                'capacity' => $restaurant['capacity'],
                'stars' => $restaurant['stars'],
                'website' => $restaurant['website'],
                'map' => $restaurant['map'],
                'operator' => $restaurant['operator'],
                'vegetarian' => $restaurant['vegetarian'],
                'vegan' => $restaurant['vegan'],
                'delivery' => $restaurant['delivery'],
                'takeaway' => $restaurant['takeaway'],
                'drive_through' => $restaurant['drive_through'],
                'wikidata' => $restaurant['wikidata'],
                'brand_wikidata' => $restaurant['brand_wikidata'],
                'facebook' => $restaurant['facebook'],
                'smoking' => $restaurant['smoking'],
                'idCommune' => $restaurant['idC']

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
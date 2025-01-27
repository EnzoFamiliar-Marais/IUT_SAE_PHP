<?php

namespace Auth;

use data\Database;
use PDO;
use PDOException;

use function PHPSTORM_META\type;

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

    public function addRestaurant($nom_Resto, $adresse, $telephone, $photos, $code_departement, $departement, $siret, $opening_hours, $internet_access, $wheelchair, $type, $longitude, $latitude, $code_commune, $commune, $code_region, $region, $brand, $capacity, $stars, $website, $map, $operator, $vegetarian, $vegan, $delivery, $takeaway, $drive_through, $wikidata, $brand_wikidata, $facebook, $smoking)
    {
        $selectStmt = $this->db->prepare('SELECT * FROM "Restaurant" WHERE "nom_Resto" = ?', [$nom_Resto]);
        $existingRestaurant = $selectStmt->fetch(PDO::FETCH_OBJ);

        if ($existingRestaurant) {
            $_SESSION['errorAdd'] = "Restaurant éxiste déjà";
            return false;
        }
        print("Restaurant n'existe pas\n");
        echo "Nom: $nom_Resto\n";
        echo "Adresse: $adresse\n";
        echo "Téléphone: $telephone\n";
        echo "Photos: $photos\n";
        echo "Code Département: $code_departement\n";
        echo "Département: $departement\n";
        echo "SIRET: $siret\n";
        echo "Opening Hours: $opening_hours\n";
        echo "Internet Access: " . ($internet_access ? 'Yes' : 'No') . "\n";
        var_dump($internet_access);
        echo "Wheelchair: " . ($wheelchair ? 'Yes' : 'No') . "\n";
        var_dump($wheelchair);
        echo gettype($wheelchair) . "\n";
        echo "Type: $type\n";
        echo "Longitude: $longitude\n";
        echo "Latitude: $latitude\n";
        echo "Code Commune: $code_commune\n";
        echo "Commune: $commune\n";
        echo "Code Region: $code_region\n";
        echo "Region: $region\n";
        echo "Brand: $brand\n";
        echo "Capacity: $capacity\n";
        echo "Stars: $stars\n";
        echo "Website: $website\n";
        echo "Map: $map\n";
        echo "Operator: $operator\n";
        echo "Vegetarian: " . ($vegetarian ? 'Yes' : 'No') . "\n";
        var_dump($vegetarian);
        echo "Vegan: " . ($vegan ? 'Yes' : 'No') . "\n";
        var_dump($vegan);
        echo "Delivery: " . ($delivery ? 'Yes' : 'No') . "\n";
        var_dump($delivery);
        echo "Takeaway: " . ($takeaway ? 'Yes' : 'No') . "\n";
        var_dump($takeaway);
        echo "Drive Through: " . ($drive_through ? 'Yes' : 'No') . "\n";
        var_dump($drive_through);
        echo "Wikidata: $wikidata\n";
        echo "Brand Wikidata: $brand_wikidata\n";
        echo "Facebook: $facebook\n";
        echo "Smoking: " . ($smoking ? 'Yes' : 'No') . "\n";
        var_dump($smoking);

        // Préparer la requête pour insérer un nouveau restaurant
        $stmt = $this->db->prepare('INSERT INTO "Restaurant" ("nom_Resto", adresse, telephone, photos, code_departement, departement, siret, opening_hours, internet_access, wheelchair, "typeR", longitude, latitude, code_commune, commune, code_region, region, brand, capacity, stars, website, map, operator, vegetarian, vegan, delivery, takeaway, drive_through, wikidata, brand_wikidata, facebook, smoking) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [$nom_Resto, $adresse, $telephone, $photos, (int)$code_departement, $departement, $siret, $opening_hours, (bool)$internet_access, (bool)$wheelchair, $type, $longitude, $latitude, (int)$code_commune, $commune, (int)$code_region, $region, $brand, (int)$capacity, (int)$stars, $website, $map, $operator, (bool)$vegetarian, (bool)$vegan, (bool)$delivery, (bool)$takeaway, (bool)$drive_through, $wikidata, $brand_wikidata, $facebook, (bool)$smoking]);


        return $stmt !== false;
    }

    public function addRestaurant2($nom_Resto, $adresse, $telephone, $photos, $siret, $opening_hours, $internet_access, $wheelchair, $type, $longitude, $latitude, $brand, $capacity, $stars, $website, $map) {
        

        // Vérifier si le restaurant existe déjà
        $selectStmt = $this->db->prepare('SELECT * FROM "Restaurants" WHERE nom = ?', [$nom_Resto]);
        $existingRestaurant = $selectStmt->fetch(PDO::FETCH_OBJ);

        if ($existingRestaurant) {
            $_SESSION['errorAdd'] = "Restaurant existe déjà";
            return false;
        }
        // var_dump($nom_Resto);
        // var_dump($adresse);
        // var_dump($telephone);
        // var_dump($photos);
        // var_dump($siret);
        // var_dump($opening_hours);
        // var_dump($internet_access);
        // var_dump($wheelchair);
        // var_dump($type);
        // var_dump($longitude);
        // var_dump($latitude);
        // var_dump($brand);
        // var_dump($capacity);
        // var_dump($stars);
        // var_dump($website);
    
        // var_dump($map);
        if($internet_access == true && $wheelchair == true){
            $stmt = $this->db->prepare(
                'INSERT INTO "Restaurants" (nom, adresse, phone, photo, siret, opening_hours, internet_access, wheelchair, "typeR", longitude, latitude, brand, capacity, stars, website, map) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', 
                [
                    $nom_Resto,
                    $adresse,
                    $telephone,
                    $photos,
                    $siret,
                    $opening_hours,
                    $internet_access,
                    $wheelchair,
                    $type,
                    $longitude,
                    $latitude,
                    $brand,
                    $capacity,
                    $stars,
                    $website,
                    $map
                ]
            );
        }else if($internet_access == true && $wheelchair == false){
            $stmt = $this->db->prepare(
                'INSERT INTO "Restaurants" (nom, adresse, phone, photo, siret, opening_hours, internet_access, "typeR", longitude, latitude, brand, capacity, stars, website, map) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', 
                [
                    $nom_Resto,
                    $adresse,
                    $telephone,
                    $photos,
                    $siret,
                    $opening_hours,
                    $internet_access,
                    $type,
                    $longitude,
                    $latitude,
                    $brand,
                    $capacity,
                    $stars,
                    $website,
                    $map
                ]
            );
        }
        else if($internet_access == false && $wheelchair == true){
            $stmt = $this->db->prepare(
                'INSERT INTO "Restaurants" (nom, adresse, phone, photo, siret, opening_hours, wheelchair, "typeR", longitude, latitude, brand, capacity, stars, website, map) 
                VALUES (?, ?, ?,  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', 
                [
                    $nom_Resto,
                    $adresse,
                    $telephone,
                    $photos,
                    $siret,
                    $opening_hours,
                    $wheelchair,
                    $type,
                    $longitude,
                    $latitude,
                    $brand,
                    $capacity,
                    $stars,
                    $website,
                    $map
                ]
            );
        }
        else{
            $stmt = $this->db->prepare(
                'INSERT INTO "Restaurants" (nom, adresse, phone, photo, siret, opening_hours, "typeR", longitude, latitude, brand, capacity, stars, website, map) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', 
                [
                    $nom_Resto,
                    $adresse,
                    $telephone,
                    $photos,
                    $siret,
                    $opening_hours,
                    $type,
                    $longitude,
                    $latitude,
                    $brand,
                    $capacity,
                    $stars,
                    $website,
                    $map
                ]
            );
        }
       

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
    }
    print("test \n");
    print(isset($testValue) ."\n");


    print("Test ajouté avec " . ($testValue ? "true" : "false") . "\n");
    
        $stmt = $this->db->getPDO()->prepare("INSERT INTO test (test) VALUES (?)");
        $stmt->execute([$testValue]);
  
}
    
    

}

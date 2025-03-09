<?php

namespace Auth;

use data\Database;
use PDO;
use PDOException;

class DBTypeCuisine
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public static function fetchTypeCuisine()
    {
        $db = new DBTypeCuisine();
        $stmt = $db->db->query('SELECT * FROM "Type_Cuisine"');
        return $stmt;
    }

    public static function getAllTypeCuisine()
    {
        $TypeCuisines = array();
        foreach (DBTypeCuisine::fetchTypeCuisine() as $TypeCuisine) {
            $TypeCuisines[] = array(
                'id' => $TypeCuisine['id_cuisine'],
                'nom' => $TypeCuisine['nom'],
                
            );
        }
        return $TypeCuisines;
    }

    public function addTypeCuisine($nomTypeCuisine) 
    {
        $selectStmt = $this->db->prepare('SELECT "id_cuisine" FROM "Type_Cuisine" WHERE nom = ?', [$nomTypeCuisine]);
        $existingType = $selectStmt->fetch(PDO::FETCH_OBJ);
    
        if ($existingType) {
           return $existingType->id_cuisine;
        }
        
        $stmt = $this->db->prepare(
            'INSERT INTO "Type_Cuisine" (nom) 
            VALUES (?)',
            [
               $nomTypeCuisine
            ]
        );
    
        
        return $this->db->lastInsertId();
    }

    public function getTypeCuisineById($id) {
        $stmt = $this->db->prepare('SELECT * FROM "Type_Cuisine" WHERE "id_cuisine" = ?', [$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

}
?>
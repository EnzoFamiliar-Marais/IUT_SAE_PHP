<?php

namespace Auth;

use data\Database;
use PDO;
use PDOException;

class DBPropose
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public static function fetchProposes()
    {
        $db = new DBPropose();
        $stmt = $db->db->query('SELECT * FROM "Propose"');
        return $stmt;
    }

    public static function getAllProposes()
    {
        $proposes = array();
        foreach (DBPropose::fetchProposes() as $propose) {
            $proposes[] = array(
                'id' => $propose['id'],
                'idCuisine' => $propose['idCuisine'],
                'idResto' => $propose['idResto'],
                
            );
        }
        return $proposes;
    }

    public function addPropose($idCuisine, $idResto) 
    {
        $selectStmt = $this->db->prepare('SELECT * FROM "Propose" WHERE "idCuisine" = ? AND "idResto" = ?', [$idCuisine, $idResto]);
        $existingPropose = $selectStmt->fetch(PDO::FETCH_OBJ);
    
        if ($existingPropose) {
            $_SESSION['errorAdd'] = "Le restaurant propose déjà ce type de cuisine";
            return false;
        }
        
        $stmt = $this->db->prepare(
            'INSERT INTO "Propose" ("idCuisine", "idResto") 
            VALUES (?, ?)',
            [
               $idCuisine, $idResto
            ]
        );
    
        
        return $stmt !== false;
    }



}
?>
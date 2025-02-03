<?php

namespace Auth;

use data\Database;
use PDO;
use PDOException;

class DBRegion
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public static function fetchRegion()
    {
        $db = new DBRegion();
        $stmt = $db->db->query('SELECT * FROM "Region"');
        return $stmt;
    }

    public static function getAllRegions()
    {
        $regions = array();
        foreach (DBRegion::fetchRegion() as $region) {
            $regions[] = array(
                'idR' => $region['idR'],
                'nom' => $region['nom'],
            );
        }
        return $regions;
    }

    public function addRegion($codeRegion, $nomRegion)
    {
        $selectStmt = $this->db->prepare('SELECT * FROM "Region" WHERE "idR" = ?', [$codeRegion]);
        $existingRegion = $selectStmt->fetch(PDO::FETCH_OBJ);

        if ($existingRegion) {
            $_SESSION['errorAdd'] = "Region existe déjà";
            return false;
        }

        $stmt = $this->db->prepare(
            'INSERT INTO "Region" ("idR", "nom") 
            VALUES (?, ?)', 
            [
                $codeRegion, $nomRegion
            ]
        );

        return $stmt !== false;
    }

    public function exists($code_region) {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM "Region" WHERE "idR" = ?', [$code_region]);
        return $stmt->fetchColumn() > 0;
    }
    
                                                                                                                                                                                                                   
 
}
?>
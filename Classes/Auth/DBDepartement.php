<?php

namespace Auth;

use data\Database;
use PDO;
use PDOException;

class DBDepartement
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public static function fetchDepartement()
    {   
        $db = new DBDepartement();
        $stmt = $db->db->query('SELECT * FROM "Departement"');
        return $stmt;
    }

    public static function getAllDepartements()
    {
        $Departements = array();
        foreach (DBDepartement::fetchDepartement() as $Departement) {
            $Departements[] = array(
                'idR' => $Departement['idR'],
                'nom' => $Departement['nom'],
                "idD" => $Departement['idD']
            );
        }
        return $Departements;
    }

    public function addDepartement($codeDepartement, $nomDepartement, $codeRegion)
    {
        $selectStmt = $this->db->prepare('SELECT * FROM "Departement" WHERE "idD" = ?', [$codeDepartement]);
        $existingDepartement = $selectStmt->fetch(PDO::FETCH_OBJ);

        if ($existingDepartement) {
            $_SESSION['errorAdd'] = "Departement existe déjà";
            return false;
        }
        
        var_dump($codeDepartement);
        var_dump($nomDepartement);
        var_dump($codeRegion);
        $stmt = $this->db->prepare(
            'INSERT INTO "Departement" ("idD", "nom", "idR") 
            VALUES (?, ?, ?)', 
            [
               $codeDepartement, $nomDepartement, $codeRegion
            ]
        );

        return $stmt !== false;
    }
    
    public function exists($code_departement) {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM "Departement" WHERE "idD" = ?', [$code_departement]);
        return $stmt->fetchColumn() > 0;
    }                                                                                                                                                                                                    
 
}
?>
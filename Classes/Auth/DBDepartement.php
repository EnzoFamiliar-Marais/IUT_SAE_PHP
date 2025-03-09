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
        $this->db = Database::getInstance()->getPDO();
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
        $selectStmt = $this->db->prepare('SELECT * FROM "Departement" WHERE "idD" = ?');
        $selectStmt->execute([$codeDepartement]);
        $existingDepartement = $selectStmt->fetch(PDO::FETCH_OBJ);

        if ($existingDepartement) {
            $_SESSION['errorAdd'] = "Departement existe déjà";
            return false;
        }
        
        $stmt = $this->db->prepare(
            'INSERT INTO "Departement" ("idD", "nom", "idR") 
            VALUES (?, ?, ?)'
        );
        
        $stmt->execute([$codeDepartement, $nomDepartement, $codeRegion]);
        return true;
    }
    
    public function exists($code_departement) {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM "Departement" WHERE "idD" = ?');
        $stmt->execute([$code_departement]);
        return $stmt->fetchColumn() > 0;
    }

    public function getDepartementById($id)
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM "Departement" WHERE "idD" = ?');
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return [
                    'idD' => $result['idD'],
                    'nom' => $result['nom'],
                    'idR' => $result['idR']
                ];
            }
            return null;
        } catch (PDOException $e) {
            error_log("Error getting departement by ID: " . $e->getMessage());
            return null;
        }
    }
}
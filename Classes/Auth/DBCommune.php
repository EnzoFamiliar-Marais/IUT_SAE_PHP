<?php

namespace Auth;

use data\Database;
use PDO;
use PDOException;

class DBCommune
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getPDO();
    }

    public static function fetchCommune()
    {
        $db = new DBCommune();
        $stmt = $db->db->query('SELECT * FROM "Commune"');
        return $stmt;
    }

    public static function getAllCommunes()
    {
        $communes = array();
        foreach (DBCommune::fetchCommune() as $commune) {
            $communes[] = array(
                'idC' => $commune['idC'],
                'nom' => $commune['nom'],
                "idD" => $commune['idD']
            );
        }
        return $communes;
    }

    public function addCommune($codeCommune, $nomCommune, $codeDepartement)
    {
        $selectStmt = $this->db->prepare('SELECT * FROM "Commune" WHERE "idC" = ?');
        $selectStmt->execute([$codeCommune]);
        $existingCommune = $selectStmt->fetch(PDO::FETCH_OBJ);

        if ($existingCommune) {
            $_SESSION['errorAdd'] = "Commune existe déjà";
            return false;
        }

        $stmt = $this->db->prepare(
            'INSERT INTO "Commune" ("idC", "nom", "idD") 
            VALUES (?, ?, ?)'
        );
        
        $stmt->execute([$codeCommune, $nomCommune, $codeDepartement]);
        return true;
    }
    
    public function exists($code_commune) {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM "Commune" WHERE "idC" = ?');
        $stmt->execute([$code_commune]);
        return $stmt->fetchColumn() > 0;
    }

    public function getCommuneById($id)
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM "Commune" WHERE "idC" = ?');
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return [
                    'idC' => $result['idC'],
                    'nom' => $result['nom'],
                    'idD' => $result['idD']
                ];
            }
            return null;
        } catch (PDOException $e) {
            error_log("Error getting commune by ID: " . $e->getMessage());
            return null;
        }
    }
}
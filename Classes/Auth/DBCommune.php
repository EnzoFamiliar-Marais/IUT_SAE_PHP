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
        $this->db = Database::getInstance();
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
                'idR' => $commune['idR'],
                'nom' => $commune['nom'],
                "idD" => $commune['idD']
            );
        }
        return $communes;
    }

    public function addCommune($codeCommune, $nomCommune, $codeDepartement)
    {
        $selectStmt = $this->db->prepare('SELECT * FROM "Commune" WHERE "idC" = ?', [$codeCommune]);
        $existingCommune = $selectStmt->fetch(PDO::FETCH_OBJ);

        if ($existingCommune) {
            $_SESSION['errorAdd'] = "Commune existe déjà";
            return false;
        }

        $stmt = $this->db->prepare(
            'INSERT INTO "Commune" ("idC", "nom", "idD") 
            VALUES (?, ?, ?)', 
            [
                $codeCommune, $nomCommune, $codeDepartement
            ]
        );

        return $stmt !== false;
    }
    
    public function exists($code_commune) {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM "Commune" WHERE "idC" = ?', [$code_commune]);
        return $stmt->fetchColumn() > 0;
    }
                                                                                                                                                                                                                   
 
}
?>
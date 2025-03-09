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
        $db = Database::getInstance()->getPDO();
        $stmt = $db->query('SELECT * FROM "Commune"');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllCommunes()
    {
        return self::fetchCommune();
    }

    public function addCommune($codeCommune, $nomCommune, $codeDepartement)
    {
        try {
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

            return $stmt->execute([$codeCommune, $nomCommune, $codeDepartement]);
        } catch (PDOException $e) {
            error_log("Error adding commune: " . $e->getMessage());
            return false;
        }
    }

    public function exists($code_commune)
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM "Commune" WHERE "idC" = ?');
        $stmt->execute([$code_commune]);
        return (bool) $stmt->fetchColumn();
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
            return false;
        } catch (PDOException $e) {
            error_log("Error getting commune by ID: " . $e->getMessage());
            return false;
        }
    }
}

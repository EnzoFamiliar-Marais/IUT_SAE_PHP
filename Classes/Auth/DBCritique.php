<?php

namespace Auth;

use data\Database;
use PDO;
use PDOException;

class DBCritique
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public static function fetchCritiques()
    {
        $db = new DBCritique();
        $stmt = $db->db->query('SELECT * FROM "Critiquer"');
        return $stmt;
    }

    public static function getAllCritiques()
    {
        $critiques = array();
        foreach (DBCritique::fetchCritiques() as $critique) {
            $critiques[] = array(
                'date_critique' => $critique['date_critique'],
                'idU' => $critique['idU'],
                'idR' => $critique['idR'],
                'note' => $critique['note'],
                'commentaire' => $critique['commentaire'],
            );
        }
        return $critiques;
    }

    public function addCritique($idUtilisateur, $idRestaurant, $note, $commentaire)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO "Critiquer" ("idU","idR", "note", "commentaire") 
            VALUES (?, ?, ?, ?)',
            [
                $idUtilisateur,
                $idRestaurant,
                $note,
                $commentaire
            ]
        );

        return $stmt !== false;
    }

    
}

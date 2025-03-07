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
                "id" => $critique['id'],
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

    public static function fetchCritiqueByUser($idUtilisateur)
    { 
        $dbCritique = new DBCritique();
        $stmt = $dbCritique->db->prepare('SELECT * FROM "Critiquer" WHERE "idU" = ?', [$idUtilisateur]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getCritiqueByUser($idUtilisateur) : array
    {
        $critiques = array();
        foreach(DBCritique::fetchCritiqueByUser($idUtilisateur) as $critique){
            $critiques[] = array(
                'id' => $critique->id,
                'date_critique' => $critique->date_critique,
                'idU' => $critique->idU,
                'idR' => $critique->idR,
                'note' => $critique->note,
                'commentaire' => $critique->commentaire,
            );
        }
        return $critiques;
    }

    public function deleteCritique($id)
    {
        $stmt = $this->db->prepare('DELETE FROM "Critiquer" WHERE "id" = ?', [$id]);
        return $stmt->execute(); 
    }

    
    
}

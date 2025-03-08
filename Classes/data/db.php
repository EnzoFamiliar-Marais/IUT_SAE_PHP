<?php
namespace data;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            $this->pdo = new PDO(
                "pgsql:host=aws-0-eu-west-3.pooler.supabase.com;port=5432;dbname=postgres;sslmode=require",
                "postgres.ibepjgntihedhmtwslxg",
                "ENZOAMINEROMAINJEAN-MARC",
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
            
            $this->pdo->exec("SET NAMES 'UTF8'");
            $this->pdo->exec("SET client_encoding = 'UTF8'");
            
            error_log("Database connection established successfully");
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPDO() {
        return $this->pdo;
    }

    public function query($query) {
        $requete = $this->pdo->query($query);
        $datas = $requete->fetchAll();
        return $datas;
    }

    public function prepare($query, array $params = []) {
        $requete = $this->pdo->prepare($query);
        $requete->execute($params);
        return $requete;
    }

    public function execute($query){
        try {
            $this->pdo->exec($query);
            return true;
        } catch (PDOException $e) {
            error_log("Execute error: " . $e->getMessage());
            return false;
        }
    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    public function close(): void{
        $this->pdo = null;
    }
}

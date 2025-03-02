<?php
namespace data;

use PDO;
use PDOException;
use PDOStatement;

class Database {
    private $host = 'aws-0-eu-west-3.pooler.supabase.com';
    private $port = '5432'; 
    private $db_name = 'postgres';
    private $username = 'postgres.ibepjgntihedhmtwslxg';
    private $password = 'ENZOAMINEROMAINJEAN-MARC'; 
    private ?PDO $db = null;
    private static $instance = null;


    public function __construct()
    {
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name};sslmode=require";
            $this->db = new PDO($dsn, $this->username, $this->password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->db;
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public function getPDO() : PDO {
        return $this->db;
    }

    public function lastInsertId() {
        return $this->db->lastInsertId();
    }

    /**
     * @param string $query
     * @return ?PDOStatement
     */
    public function query(string $query){
        $requete = $this->getPDO()->query($query);
        $datas = $requete->fetchAll();
        return $datas;
    }


    public function prepare(string $query, array $params) {
        $requete = $this->getPDO()->prepare($query);
        $requete->execute($params);
        return $requete;
    }

    public function execute(string $query){
        try {
            $this->getPDO()->exec($query);
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    
    /**
     * @return void
     */
    public function close(): void{
        $this->db = null;
    }

    /**
     * @return Database
     */
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
}
?>

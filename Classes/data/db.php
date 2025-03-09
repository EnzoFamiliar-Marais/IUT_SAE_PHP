<?php
namespace data;

use PDO;
use PDOException;
use PDOStatement;

class Database {
    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;
    private ?PDO $db = null;
    private static $instance = null;


    public function __construct()
    {
        try {
            $config_path = dirname(dirname(dirname(__FILE__))) . '/config.php';
            $config = require $config_path;
            
            $this->host = $config['host'];
            $this->port = $config['port'];
            $this->db_name = $config['db_name'];
            $this->username = $config['username'];
            $this->password = $config['password'];
            
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

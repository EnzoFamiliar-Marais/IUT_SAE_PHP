<?php

namespace data;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $pdo;
    private $config;

    private function __construct()
    {
        $this->loadConfig();
        $this->connect();
    }

    private function loadConfig()
    {
        $configPath = dirname(dirname(__DIR__)) . '/config.php';
        if (!file_exists($configPath)) {
            throw new \RuntimeException('Le fichier de configuration est manquant');
        }
        $this->config = require $configPath;
    }

    private function connect()
    {
        try {
            $dbConfig = $this->config['database'];
            $dsn = sprintf(
                "pgsql:host=%s;port=%s;dbname=%s",
                $dbConfig['host'],
                $dbConfig['port'],
                $dbConfig['dbname']
            );

            $this->pdo = new PDO(
                $dsn,
                $dbConfig['user'],
                $dbConfig['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            error_log("Erreur de connexion à la base de données : " . $e->getMessage());
            throw new \RuntimeException('Impossible de se connecter à la base de données');
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    // Empêcher la duplication de l'instance
    private function __clone() {}
    private function __wakeup() {}
}

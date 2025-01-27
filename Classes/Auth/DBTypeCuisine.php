<?php

namespace Auth;

use data\Database;

class DBTypeCuisine
{

    private $db;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config.php';
        $this->db = Database::getInstance($config['db'])->getPDO();
    }
}

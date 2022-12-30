<?php

namespace DB;

use PDO;

class DB {
    private $dbHostname = 'localhost';
    private $dbUsername = 'root';
    private $dbPassword = 'mysql';
    private $dbName = 'slim_app';
    
    public function connect() {
        $connectionString = "mysql:host={$this->dbHostname};dbname={$this->dbName}";
        $pdo = new PDO($connectionString, $this->dbUsername, $this->dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    }
}
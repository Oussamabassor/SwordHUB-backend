<?php

/**
 * Legacy MySQL Database Configuration
 * 
 * This file is kept for backward compatibility but is no longer used.
 * The application now uses MongoDB. See config/MongoDB.php
 * 
 * @deprecated Use MongoDB.php instead
 */

class Database {
    private $host = "localhost";
    private $db_name = "ecommerce_db";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        trigger_error(
            'Database.php is deprecated. Use MongoDB.php instead',
            E_USER_DEPRECATED
        );
        
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
        return $this->conn;
    }
}


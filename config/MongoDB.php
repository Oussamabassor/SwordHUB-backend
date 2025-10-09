<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

class MongoDB {
    private static $instance = null;
    private $client;
    private $database;

    private function __construct() {
        // Load environment variables
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        $uri = $_ENV['MONGODB_URI'] ?? 'mongodb://localhost:27017';
        $dbName = $_ENV['MONGODB_DATABASE'] ?? 'swordhub';

        try {
            // Configure options for MongoDB connection
            $uriOptions = [];
            $driverOptions = [];
            
            // Add SSL/TLS options if using MongoDB Atlas (mongodb+srv://)
            if (strpos($uri, 'mongodb+srv://') !== false || strpos($uri, 'mongodb://') !== false && strpos($uri, 'mongodb.net') !== false) {
                // Use only URI options (not deprecated driver options)
                $uriOptions = [
                    'tls' => true,
                    'tlsAllowInvalidCertificates' => true,
                    'tlsAllowInvalidHostnames' => true,
                ];
            }
            
            // Create client with options (no driver options to avoid deprecation warnings)
            $this->client = new MongoDB\Client($uri, $uriOptions, $driverOptions);
            $this->database = $this->client->selectDatabase($dbName);
            
            // Test connection with timeout
            $this->client->listDatabases(['maxTimeMS' => 5000]);
            
            error_log("MongoDB Connection: Successfully connected to " . $dbName);
        } catch (Exception $e) {
            error_log("MongoDB Connection Error: " . $e->getMessage());
            
            // Provide more helpful error message
            $errorMsg = "Failed to connect to MongoDB";
            if (strpos($e->getMessage(), 'TLS') !== false || strpos($e->getMessage(), 'SSL') !== false) {
                $errorMsg .= " (SSL/TLS Error - See FINAL_SOLUTION.md)";
            }
            throw new Exception($errorMsg . ": " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->database;
    }

    public function getDatabase() {
        return $this->database;
    }

    public function getCollection($collectionName) {
        return $this->database->selectCollection($collectionName);
    }
}

<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

class MongoDB {
    private static $instance = null;
    private $client;
    private $database;

    private function __construct() {
        // Load environment variables (optional in production)
        try {
            if (file_exists(__DIR__ . '/../.env')) {
                $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
                $dotenv->load();
            }
        } catch (Exception $e) {
            // In production, environment variables are set by the hosting platform
        }

        $uri = $_ENV['MONGODB_URI'] ?? getenv('MONGODB_URI') ?? 'mongodb://localhost:27017';
        $dbName = $_ENV['MONGODB_DATABASE'] ?? getenv('MONGODB_DATABASE') ?? 'swordhub';

        try {
            // Configure options for MongoDB connection (simplified)
            $uriOptions = [];
            
            // Add SSL/TLS options if using MongoDB Atlas (mongodb+srv://)
            if (strpos($uri, 'mongodb+srv://') !== false || strpos($uri, 'mongodb.net') !== false) {
                $uriOptions = [
                    'tls' => true,
                    'tlsAllowInvalidCertificates' => true,
                ];
            }
            
            // Create client with simplified options (no driver options)
            $this->client = new MongoDB\Client($uri, $uriOptions);
            $this->database = $this->client->selectDatabase($dbName);
            
            // Test connection with ping command
            $this->database->command(['ping' => 1]);
            
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

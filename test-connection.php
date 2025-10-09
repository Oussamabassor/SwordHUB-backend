<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/MongoDB.php';

echo "Testing MongoDB Atlas Connection...\n\n";

try {
    // Get MongoDB instance
    $mongodb = MongoDB::getInstance();
    $db = $mongodb->getDatabase();
    
    echo "✓ Successfully connected to MongoDB Atlas!\n";
    echo "Database Name: " . $db->getDatabaseName() . "\n\n";
    
    // List collections
    echo "Available Collections:\n";
    $collections = $db->listCollections();
    $hasCollections = false;
    foreach ($collections as $collection) {
        echo "  - " . $collection->getName() . "\n";
        $hasCollections = true;
    }
    
    if (!$hasCollections) {
        echo "  (No collections found - database is empty)\n";
    }
    
    echo "\n✓ Connection test completed successfully!\n";
    
} catch (Exception $e) {
    echo "✗ Connection failed: " . $e->getMessage() . "\n";
    echo "\nPlease check:\n";
    echo "  1. MongoDB extension is enabled in php.ini\n";
    echo "  2. Connection string is correct\n";
    echo "  3. Network allows connections to MongoDB Atlas\n";
    echo "  4. Database credentials are valid\n";
}

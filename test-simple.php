<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Step 1: Loading autoload...\n";
try {
    require_once __DIR__ . '/vendor/autoload.php';
    echo "✓ Autoload loaded\n";
} catch (Exception $e) {
    echo "✗ Autoload error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\nStep 2: Loading MongoDB config...\n";
try {
    require_once __DIR__ . '/config/MongoDB.php';
    echo "✓ MongoDB config loaded\n";
} catch (Exception $e) {
    echo "✗ MongoDB config error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\nStep 3: Getting MongoDB instance...\n";
try {
    $db = MongoDB::getInstance();
    echo "✓ MongoDB instance created\n";
} catch (Exception $e) {
    echo "✗ MongoDB connection error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

echo "\nStep 4: Testing database query...\n";
try {
    $collections = $db->listCollections();
    echo "✓ Collections found: ";
    foreach ($collections as $collection) {
        echo $collection->getName() . " ";
    }
    echo "\n";
} catch (Exception $e) {
    echo "✗ Query error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n✓ All tests passed!\n";

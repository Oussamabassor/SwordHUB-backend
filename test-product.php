<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Loading autoload...\n";
require_once __DIR__ . '/vendor/autoload.php';

echo "Loading MongoDB config...\n";
require_once __DIR__ . '/config/MongoDB.php';

echo "Getting MongoDB instance...\n";
try {
    $db = MongoDB::getInstance();
    echo "✓ Got database instance\n";
    echo "Database type: " . get_class($db) . "\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\nLoading Product model...\n";
require_once __DIR__ . '/models/Product.php';

echo "Creating Product instance...\n";
try {
    $product = new Product();
    echo "✓ Product model created\n";
} catch (Exception $e) {
    echo "✗ Error creating Product: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}

echo "\nFetching products...\n";
try {
    $result = $product->findAll(['page' => 1, 'limit' => 5]);
    echo "✓ Found " . $result['total'] . " products\n";
    echo "Products in page: " . count($result['products']) . "\n";
} catch (Exception $e) {
    echo "✗ Error fetching products: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}

echo "\n✓ All tests passed!\n";

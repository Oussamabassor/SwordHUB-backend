<?php

// Minimal test server
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load dependencies
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/MongoDB.php';
require_once __DIR__ . '/models/Product.php';

// Load environment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    $productModel = new Product();
    $result = $productModel->findAll(['page' => 1, 'limit' => 10]);
    
    // Convert MongoDB ObjectIds to strings
    foreach ($result['products'] as &$product) {
        $product['id'] = (string)$product['_id'];
        unset($product['_id']);
    }
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $result
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

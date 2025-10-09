<?php

/**
 * DEPRECATED - This endpoint is no longer used
 * 
 * Please use the new REST API endpoints:
 * GET /api/products - Get all products
 * GET /api/products/:id - Get single product
 * 
 * This file is kept for backward compatibility only.
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

http_response_code(301);
header("Location: /api/products");

echo json_encode([
    'success' => false,
    'message' => 'This endpoint is deprecated. Please use /api/products instead.',
    'deprecated' => true,
    'newEndpoint' => '/api/products'
]);
exit();


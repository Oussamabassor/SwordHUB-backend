<?php

// Serve static files from uploads directory
if (php_sapi_name() === 'cli-server') {
    $requestUri = $_SERVER['REQUEST_URI'];
    $path = parse_url($requestUri, PHP_URL_PATH);
    
    // Check if this is a request for an uploaded file
    if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $path)) {
        // Remove leading slash and ./
        $filePath = ltrim($path, '/');
        $filePath = str_replace('./', '', $filePath);
        
        if (file_exists($filePath)) {
            $mimeType = mime_content_type($filePath);
            header("Content-Type: $mimeType");
            header("Content-Length: " . filesize($filePath));
            readfile($filePath);
            exit();
        }
    }
}

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Load dependencies
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/middleware/ErrorMiddleware.php';
require_once __DIR__ . '/routes/AuthRoutes.php';
require_once __DIR__ . '/routes/ProductRoutes.php';
require_once __DIR__ . '/routes/CategoryRoutes.php';
require_once __DIR__ . '/routes/OrderRoutes.php';
require_once __DIR__ . '/routes/DashboardRoutes.php';

// Set error handlers
set_exception_handler(['ErrorMiddleware', 'handleException']);
set_error_handler(['ErrorMiddleware', 'handleError']);

// Load environment variables (optional in production - uses system env vars)
try {
    if (file_exists(__DIR__ . '/.env')) {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    }
} catch (Exception $e) {
    // In production, environment variables are set by the hosting platform
    // Continue without .env file
}

// CORS Configuration
$allowedOrigins = [
    $_ENV['FRONTEND_URL'] ?? 'http://localhost:5173',
    'http://localhost:5173',
    'http://localhost:3000'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    header("Access-Control-Allow-Origin: " . $allowedOrigins[0]);
}

header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 3600");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Set JSON content type
header("Content-Type: application/json; charset=UTF-8");

// Get request method and URI
$method = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

// Remove query string and parse path
$path = parse_url($requestUri, PHP_URL_PATH);
$path = str_replace('/api', '', $path);

// Router
try {
    // Root endpoint
    if ($path === '/' && $method === 'GET') {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'SwordHUB API is running!',
            'version' => '1.0',
            'endpoints' => [
                'health' => '/health',
                'auth' => '/api/auth',
                'products' => '/api/products',
                'categories' => '/api/categories',
                'orders' => '/api/orders',
                'dashboard' => '/api/dashboard'
            ],
            'timestamp' => time()
        ]);
        exit();
    }

    // Health check
    if ($path === '/health' && $method === 'GET') {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Server is running',
            'timestamp' => time()
        ]);
        exit();
    }

    // Auth routes
    if (strpos($path, '/auth') === 0) {
        $authRoutes = new AuthRoutes();
        $subPath = substr($path, 5); // Remove '/auth'
        if ($authRoutes->handle($method, $subPath)) {
            exit();
        }
    }

    // Product routes
    if (strpos($path, '/products') === 0) {
        $productRoutes = new ProductRoutes();
        $subPath = substr($path, 9); // Remove '/products'
        if ($productRoutes->handle($method, $subPath, $_GET)) {
            exit();
        }
    }

    // Category routes
    if (strpos($path, '/categories') === 0) {
        $categoryRoutes = new CategoryRoutes();
        $subPath = substr($path, 11); // Remove '/categories'
        if ($categoryRoutes->handle($method, $subPath)) {
            exit();
        }
    }

    // Order routes
    if (strpos($path, '/orders') === 0) {
        $orderRoutes = new OrderRoutes();
        $subPath = substr($path, 7); // Remove '/orders'
        if ($orderRoutes->handle($method, $subPath)) {
            exit();
        }
    }

    // Dashboard routes
    if (strpos($path, '/dashboard') === 0) {
        $dashboardRoutes = new DashboardRoutes();
        $subPath = substr($path, 10); // Remove '/dashboard'
        if ($dashboardRoutes->handle($method, $subPath)) {
            exit();
        }
    }

    // If no route matched
    ErrorMiddleware::notFound();

} catch (Exception $e) {
    ErrorMiddleware::handleException($e);
}

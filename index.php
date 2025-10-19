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

// CORS headers are now handled by Apache in docker/000-default.conf
// Removed duplicate CORS configuration to fix "multiple values" error

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

// Remove trailing slashes (except root)
$path = $path !== '/' ? rtrim($path, '/') : $path;

// Remove /api prefix for routing
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
    
    // Simple test endpoint (no dependencies)
    if ($path === '/test' && $method === 'GET') {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Simple test endpoint working',
            'path' => $path,
            'method' => $method,
            'get_params' => $_GET
        ]);
        exit();
    }
    
    // Debug endpoint to check environment variables
    if ($path === '/debug' && $method === 'GET') {
        try {
            // Test MongoDB connection
            require_once __DIR__ . '/config/MongoDB.php';
            $mongodb = MongoDB::getInstance();
            $mongoStatus = 'Connected ✓';
            $mongoError = null;
        } catch (Exception $e) {
            $mongoStatus = 'Failed ✗';
            $mongoError = $e->getMessage();
        }
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'env_check' => [
                'MONGODB_URI' => !empty($_ENV['MONGODB_URI']) || !empty(getenv('MONGODB_URI')) ? 'Set ✓' : 'Missing ✗',
                'MONGODB_DATABASE' => !empty($_ENV['MONGODB_DATABASE']) || !empty(getenv('MONGODB_DATABASE')) ? 'Set ✓' : 'Missing ✗',
                'JWT_SECRET' => !empty($_ENV['JWT_SECRET']) || !empty(getenv('JWT_SECRET')) ? 'Set ✓' : 'Missing ✗',
                'CLOUDINARY_CLOUD_NAME' => !empty($_ENV['CLOUDINARY_CLOUD_NAME']) || !empty(getenv('CLOUDINARY_CLOUD_NAME')) ? 'Set ✓' : 'Missing ✗',
            ],
            'mongodb_connection' => [
                'status' => $mongoStatus,
                'error' => $mongoError
            ],
            'php_version' => phpversion(),
            'extensions' => [
                'mongodb' => extension_loaded('mongodb') ? 'Loaded ✓' : 'Missing ✗',
                'pdo' => extension_loaded('pdo') ? 'Loaded ✓' : 'Missing ✗',
            ]
        ]);
        exit();
    }
    
    // Test MongoDB query endpoint
    if ($path === '/test-db' && $method === 'GET') {
        try {
            require_once __DIR__ . '/config/MongoDB.php';
            $mongodb = MongoDB::getInstance();
            $collection = $mongodb->getCollection('products');
            
            // Simple count query
            $count = $collection->countDocuments();
            
            // Get first 3 products
            $products = $collection->find([], ['limit' => 3])->toArray();
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'MongoDB query successful',
                'products_count' => $count,
                'sample_products' => count($products),
                'first_product' => !empty($products) ? [
                    'id' => (string)$products[0]['_id'],
                    'name' => $products[0]['name'] ?? 'N/A'
                ] : null
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'MongoDB query failed',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        exit();
    }

    // Error log endpoint (for debugging)
    if ($path === '/error-log' && $method === 'GET') {
        http_response_code(200);
        header('Content-Type: application/json');
        
        $logFile = __DIR__ . '/error.log';
        if (file_exists($logFile)) {
            $logContent = file_get_contents($logFile);
            $lines = explode("\n", $logContent);
            // Get last 50 lines
            $recentLines = array_slice($lines, -50);
            
            echo json_encode([
                'success' => true,
                'log_file' => $logFile,
                'total_lines' => count($lines),
                'recent_errors' => implode("\n", $recentLines)
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error log file not found',
                'expected_path' => $logFile
            ]);
        }
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

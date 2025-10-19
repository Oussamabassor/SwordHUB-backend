<?php

require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../middleware/AdminMiddleware.php';

class ProductRoutes {
    private $controller;

    public function __construct() {
        try {
            $this->controller = new ProductController();
        } catch (Exception $e) {
            error_log("ProductRoutes __construct Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to initialize ProductController',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            exit();
        }
    }

    public function handle($method, $path, $params) {
        // GET /api/products
        if ($path === '' && $method === 'GET') {
            $this->controller->getAll();
            return true;
        }

        // GET /api/products/:id
        if (preg_match('/^\/([a-f0-9]{24})$/', $path, $matches) && $method === 'GET') {
            $this->controller->getById($matches[1]);
            return true;
        }

        // POST /api/products (Admin only)
        if ($path === '' && $method === 'POST') {
            AuthMiddleware::authenticate();
            AdminMiddleware::checkAdmin();
            $this->controller->create();
            return true;
        }

        // PUT /api/products/:id (Admin only)
        if (preg_match('/^\/([a-f0-9]{24})$/', $path, $matches) && $method === 'PUT') {
            AuthMiddleware::authenticate();
            AdminMiddleware::checkAdmin();
            $this->controller->update($matches[1]);
            return true;
        }

        // DELETE /api/products/:id (Admin only)
        if (preg_match('/^\/([a-f0-9]{24})$/', $path, $matches) && $method === 'DELETE') {
            AuthMiddleware::authenticate();
            AdminMiddleware::checkAdmin();
            $this->controller->delete($matches[1]);
            return true;
        }

        // POST /api/products/upload (Admin only)
        if ($path === '/upload' && $method === 'POST') {
            AuthMiddleware::authenticate();
            AdminMiddleware::checkAdmin();
            $this->controller->upload();
            return true;
        }

        return false;
    }
}

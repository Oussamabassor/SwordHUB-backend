<?php

require_once __DIR__ . '/../controllers/OrderController.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../middleware/AdminMiddleware.php';

class OrderRoutes {
    private $controller;

    public function __construct() {
        $this->controller = new OrderController();
    }

    public function handle($method, $path) {
        // GET /api/orders (Admin only)
        if ($path === '' && $method === 'GET') {
            AuthMiddleware::authenticate();
            AdminMiddleware::checkAdmin();
            $this->controller->getAll();
            return true;
        }

        // GET /api/orders/stats (Admin only)
        if ($path === '/stats' && $method === 'GET') {
            AuthMiddleware::authenticate();
            AdminMiddleware::checkAdmin();
            $this->controller->getStats();
            return true;
        }

        // GET /api/orders/:id
        if (preg_match('/^\/([a-f0-9]{24})$/', $path, $matches) && $method === 'GET') {
            $this->controller->getById($matches[1]);
            return true;
        }

        // POST /api/orders
        if ($path === '' && $method === 'POST') {
            $this->controller->create();
            return true;
        }

        // PATCH /api/orders/:id/status (Admin only)
        if (preg_match('/^\/([a-f0-9]{24})\/status$/', $path, $matches) && $method === 'PATCH') {
            AuthMiddleware::authenticate();
            AdminMiddleware::checkAdmin();
            $this->controller->updateStatus($matches[1]);
            return true;
        }

        // DELETE /api/orders/:id (Admin only)
        if (preg_match('/^\/([a-f0-9]{24})$/', $path, $matches) && $method === 'DELETE') {
            AuthMiddleware::authenticate();
            AdminMiddleware::checkAdmin();
            $this->controller->delete($matches[1]);
            return true;
        }

        return false;
    }
}

<?php

require_once __DIR__ . '/../controllers/CategoryController.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../middleware/AdminMiddleware.php';

class CategoryRoutes {
    private $controller;

    public function __construct() {
        $this->controller = new CategoryController();
    }

    public function handle($method, $path) {
        // GET /api/categories
        if ($path === '' && $method === 'GET') {
            $this->controller->getAll();
            return true;
        }

        // GET /api/categories/:id
        if (preg_match('/^\/([a-f0-9]{24})$/', $path, $matches) && $method === 'GET') {
            $this->controller->getById($matches[1]);
            return true;
        }

        // POST /api/categories (Admin only)
        if ($path === '' && $method === 'POST') {
            AuthMiddleware::authenticate();
            AdminMiddleware::checkAdmin();
            $this->controller->create();
            return true;
        }

        // PUT /api/categories/:id (Admin only)
        if (preg_match('/^\/([a-f0-9]{24})$/', $path, $matches) && $method === 'PUT') {
            AuthMiddleware::authenticate();
            AdminMiddleware::checkAdmin();
            $this->controller->update($matches[1]);
            return true;
        }

        // DELETE /api/categories/:id (Admin only)
        if (preg_match('/^\/([a-f0-9]{24})$/', $path, $matches) && $method === 'DELETE') {
            AuthMiddleware::authenticate();
            AdminMiddleware::checkAdmin();
            $this->controller->delete($matches[1]);
            return true;
        }

        return false;
    }
}

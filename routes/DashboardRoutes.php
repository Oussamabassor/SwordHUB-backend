<?php

require_once __DIR__ . '/../controllers/DashboardController.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../middleware/AdminMiddleware.php';

class DashboardRoutes {
    private $controller;

    public function __construct() {
        $this->controller = new DashboardController();
    }

    public function handle($method, $path) {
        // All dashboard routes require admin authentication
        AuthMiddleware::authenticate();
        AdminMiddleware::checkAdmin();

        // GET /api/dashboard/stats
        if ($path === '/stats' && $method === 'GET') {
            $this->controller->getStats();
            return true;
        }

        // GET /api/dashboard/analytics
        if ($path === '/analytics' && $method === 'GET') {
            $this->controller->getAnalytics();
            return true;
        }

        return false;
    }
}

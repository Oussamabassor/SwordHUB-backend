<?php

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class AuthRoutes {
    private $controller;

    public function __construct() {
        $this->controller = new AuthController();
    }

    public function handle($method, $path) {
        if ($path === '/login' && $method === 'POST') {
            $this->controller->login();
            return true;
        }

        if ($path === '/logout' && $method === 'POST') {
            AuthMiddleware::authenticate();
            $this->controller->logout();
            return true;
        }

        if ($path === '/me' && $method === 'GET') {
            AuthMiddleware::authenticate();
            $this->controller->me();
            return true;
        }

        if ($path === '/register' && $method === 'POST') {
            $this->controller->register();
            return true;
        }

        return false;
    }
}

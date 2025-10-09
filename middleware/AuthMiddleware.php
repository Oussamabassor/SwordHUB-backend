<?php

require_once __DIR__ . '/../utils/JWT.php';
require_once __DIR__ . '/../models/User.php';

class AuthMiddleware {
    public static function authenticate() {
        $headers = getallheaders();
        
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'No authorization token provided'
            ]);
            exit();
        }

        $authHeader = $headers['Authorization'];
        
        // Extract token from "Bearer <token>"
        if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Invalid authorization format'
            ]);
            exit();
        }

        $token = $matches[1];
        $payload = JWT::decode($token);

        if (!$payload) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Invalid or expired token'
            ]);
            exit();
        }

        // Attach user info to request
        global $currentUser;
        $userModel = new User();
        $currentUser = $userModel->findById($payload['userId']);

        if (!$currentUser) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'User not found'
            ]);
            exit();
        }

        return $currentUser;
    }
}

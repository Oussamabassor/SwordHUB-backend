<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/JWT.php';
require_once __DIR__ . '/../utils/Validator.php';
require_once __DIR__ . '/../middleware/RateLimiter.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        // Apply rate limiting
        RateLimiter::check();

        $data = json_decode(file_get_contents("php://input"), true);

        // Validate input
        $errors = Validator::validateRequired($data, ['email', 'password']);
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ]);
            return;
        }

        // Validate email format
        $emailError = Validator::validateEmail($data['email']);
        if ($emailError) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $emailError
            ]);
            return;
        }

        // Find user
        $user = $this->userModel->findByEmail($data['email']);
        
        if (!$user || !$this->userModel->verifyPassword($data['password'], $user['password'])) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Invalid email or password'
            ]);
            return;
        }

        // Generate JWT token
        $token = JWT::encode([
            'userId' => (string)$user['_id'],
            'email' => $user['email'],
            'role' => $user['role']
        ]);

        // Remove password from response
        unset($user['password']);
        $user['id'] = (string)$user['_id'];
        unset($user['_id']);

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout() {
        // JWT logout is handled client-side by removing the token
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function me() {
        global $currentUser;
        
        if (!$currentUser) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Not authenticated'
            ]);
            return;
        }

        $user = $currentUser;
        $user['id'] = (string)$user['_id'];
        unset($user['_id']);

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $user
        ]);
    }

    public function register() {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate input
        $errors = Validator::validateRequired($data, ['name', 'email', 'password']);
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ]);
            return;
        }

        // Validate email
        $emailError = Validator::validateEmail($data['email']);
        if ($emailError) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $emailError
            ]);
            return;
        }

        // Validate password length
        if (strlen($data['password']) < 6) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Password must be at least 6 characters'
            ]);
            return;
        }

        try {
            $user = $this->userModel->create([
                'name' => Validator::sanitizeString($data['name']),
                'email' => Validator::sanitizeString($data['email']),
                'password' => $data['password'],
                'role' => 'customer'
            ]);

            // Generate JWT token for auto-login after registration
            $token = JWT::encode([
                'userId' => (string)$user['_id'],
                'email' => $user['email'],
                'role' => $user['role']
            ]);

            // Remove password from response
            unset($user['password']);
            $user['id'] = (string)$user['_id'];
            unset($user['_id']);

            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'User registered successfully',
                'token' => $token,
                'user' => $user
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}

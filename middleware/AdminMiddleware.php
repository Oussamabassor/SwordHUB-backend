<?php

class AdminMiddleware {
    public static function checkAdmin() {
        global $currentUser;
        
        if (!isset($currentUser)) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Authentication required'
            ]);
            exit();
        }

        if ($currentUser['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'Access denied. Admin privileges required'
            ]);
            exit();
        }

        return true;
    }
}

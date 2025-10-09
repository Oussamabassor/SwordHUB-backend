<?php

class ErrorMiddleware {
    public static function handleException($e) {
        error_log("Error: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());

        http_response_code(500);
        
        $response = [
            'success' => false,
            'message' => $e->getMessage()
        ];

        // In development, include stack trace
        if ($_ENV['APP_ENV'] === 'development') {
            $response['trace'] = $e->getTraceAsString();
        }

        echo json_encode($response);
        exit();
    }

    public static function handleError($errno, $errstr, $errfile, $errline) {
        error_log("PHP Error [$errno]: $errstr in $errfile on line $errline");
        
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Internal server error'
        ]);
        exit();
    }

    public static function notFound() {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint not found'
        ]);
        exit();
    }
}

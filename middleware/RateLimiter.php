<?php

class RateLimiter {
    private static $storage = [];
    private static $window;
    private static $maxRequests;

    public static function init() {
        self::$window = (int)($_ENV['RATE_LIMIT_WINDOW'] ?? 900); // 15 minutes default
        self::$maxRequests = (int)($_ENV['RATE_LIMIT_MAX_REQUESTS'] ?? 100);
    }

    public static function check($identifier = null) {
        self::init();
        
        if ($identifier === null) {
            $identifier = $_SERVER['REMOTE_ADDR'];
        }

        $now = time();
        $key = $identifier . '_' . floor($now / self::$window);

        // Clean old entries (simple memory cleanup)
        self::cleanOldEntries($now);

        if (!isset(self::$storage[$key])) {
            self::$storage[$key] = ['count' => 0, 'timestamp' => $now];
        }

        self::$storage[$key]['count']++;

        if (self::$storage[$key]['count'] > self::$maxRequests) {
            http_response_code(429);
            echo json_encode([
                'success' => false,
                'message' => 'Too many requests. Please try again later.'
            ]);
            exit();
        }

        return true;
    }

    private static function cleanOldEntries($now) {
        foreach (self::$storage as $key => $data) {
            if ($now - $data['timestamp'] > self::$window * 2) {
                unset(self::$storage[$key]);
            }
        }
    }
}

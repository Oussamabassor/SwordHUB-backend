<?php

class FileUpload {
    private static $uploadDir;
    private static $maxFileSize;
    private static $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];

    public static function init() {
        self::$uploadDir = $_ENV['UPLOAD_DIR'] ?? './uploads';
        self::$maxFileSize = (int)($_ENV['MAX_FILE_SIZE'] ?? 5242880); // 5MB default
        
        // Create upload directory if it doesn't exist
        if (!file_exists(self::$uploadDir)) {
            mkdir(self::$uploadDir, 0777, true);
        }
        
        // Create products subdirectory
        $productsDir = self::$uploadDir . '/products';
        if (!file_exists($productsDir)) {
            mkdir($productsDir, 0777, true);
        }
    }

    public static function uploadImage($file) {
        self::init();
        
        // Validate file
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return ['success' => false, 'message' => 'No file uploaded'];
        }
        
        // Check file size
        if ($file['size'] > self::$maxFileSize) {
            return ['success' => false, 'message' => 'File size exceeds maximum allowed size'];
        }
        
        // Check file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, self::$allowedTypes)) {
            return ['success' => false, 'message' => 'Invalid file type. Only images are allowed'];
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $filepath = self::$uploadDir . '/products/' . $filename;
        
        // Move file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $imageUrl = $protocol . '://' . $host . '/' . $filepath;
            
            return ['success' => true, 'imageUrl' => $imageUrl];
        }
        
        return ['success' => false, 'message' => 'Failed to upload file'];
    }

    public static function deleteImage($imageUrl) {
        if (empty($imageUrl)) {
            return false;
        }
        
        // Extract filename from URL
        $filename = basename(parse_url($imageUrl, PHP_URL_PATH));
        $filepath = self::$uploadDir . '/products/' . $filename;
        
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        
        return false;
    }
}

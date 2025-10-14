<?php

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\Quality;
use Cloudinary\Transformation\Format;

class FileUpload {
    private static $uploadDir;
    private static $maxFileSize;
    private static $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    private static $cloudinary = null;
    private static $useCloudinary = false;

    public static function init() {
        self::$uploadDir = $_ENV['UPLOAD_DIR'] ?? './uploads';
        self::$maxFileSize = (int)($_ENV['MAX_FILE_SIZE'] ?? 5242880); // 5MB default
        
        // Check if Cloudinary should be used
        self::$useCloudinary = filter_var(
            $_ENV['USE_CLOUDINARY'] ?? 'false',
            FILTER_VALIDATE_BOOLEAN
        );

        // Initialize Cloudinary if credentials are available
        if (self::$useCloudinary) {
            $cloudName = $_ENV['CLOUDINARY_CLOUD_NAME'] ?? null;
            $apiKey = $_ENV['CLOUDINARY_API_KEY'] ?? null;
            $apiSecret = $_ENV['CLOUDINARY_API_SECRET'] ?? null;

            if ($cloudName && $apiKey && $apiSecret) {
                try {
                    self::$cloudinary = new Cloudinary([
                        'cloud' => [
                            'cloud_name' => $cloudName,
                            'api_key' => $apiKey,
                            'api_secret' => $apiSecret
                        ]
                    ]);
                } catch (Exception $e) {
                    error_log("Cloudinary initialization failed: " . $e->getMessage());
                    self::$useCloudinary = false;
                }
            } else {
                self::$useCloudinary = false;
            }
        }

        // Create local upload directory if not using Cloudinary or as fallback
        if (!self::$useCloudinary) {
            if (!file_exists(self::$uploadDir)) {
                mkdir(self::$uploadDir, 0777, true);
            }
            
            $productsDir = self::$uploadDir . '/products';
            if (!file_exists($productsDir)) {
                mkdir($productsDir, 0777, true);
            }
        }
    }

    public static function uploadImage($file) {
        self::init();
        
        // Validate file
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return ['success' => false, 'message' => 'No file uploaded'];
        }
        
        // Check file size (5MB max)
        if ($file['size'] > self::$maxFileSize) {
            return ['success' => false, 'message' => 'File size exceeds 5MB maximum'];
        }
        
        // Check file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, self::$allowedTypes)) {
            return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, and WebP images are allowed'];
        }

        // Use Cloudinary if configured
        if (self::$useCloudinary && self::$cloudinary) {
            return self::uploadToCloudinary($file);
        }

        // Fallback to local upload
        return self::uploadToLocal($file);
    }

    private static function uploadToCloudinary($file) {
        try {
            $folder = $_ENV['CLOUDINARY_FOLDER'] ?? 'swordhub/products';
            
            // Upload with optimizations
            $result = self::$cloudinary->uploadApi()->upload($file['tmp_name'], [
                'folder' => $folder,
                'resource_type' => 'image',
                'transformation' => [
                    [
                        'width' => 1200,
                        'height' => 1200,
                        'crop' => 'limit',  // Don't upscale, only downscale if larger
                        'quality' => 'auto:good',  // Automatic quality optimization
                        'fetch_format' => 'auto'   // Automatic format selection (WebP for modern browsers)
                    ]
                ],
                'allowed_formats' => ['jpg', 'png', 'gif', 'webp'],
                'overwrite' => false,
                'unique_filename' => true,
                'use_filename' => true,
                'filename_override' => pathinfo($file['name'], PATHINFO_FILENAME) . '_' . time()
            ]);

            if (isset($result['secure_url'])) {
                return [
                    'success' => true,
                    'imageUrl' => $result['secure_url'],
                    'public_id' => $result['public_id'],
                    'width' => $result['width'],
                    'height' => $result['height'],
                    'format' => $result['format'],
                    'bytes' => $result['bytes']
                ];
            }

            return ['success' => false, 'message' => 'Cloudinary upload failed'];
        } catch (Exception $e) {
            error_log("Cloudinary upload error: " . $e->getMessage());
            
            // Fallback to local upload if Cloudinary fails
            return self::uploadToLocal($file);
        }
    }

    private static function uploadToLocal($file) {
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $filepath = self::$uploadDir . '/products/' . $filename;
        
        // Move file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $imageUrl = $protocol . '://' . $host . '/' . $filepath;
            
            return [
                'success' => true,
                'imageUrl' => $imageUrl,
                'storage' => 'local'
            ];
        }
        
        return ['success' => false, 'message' => 'Failed to upload file'];
    }

    public static function deleteImage($imageUrl) {
        if (empty($imageUrl)) {
            return false;
        }

        self::init();

        // Check if this is a Cloudinary URL
        if (self::$useCloudinary && strpos($imageUrl, 'cloudinary.com') !== false) {
            return self::deleteFromCloudinary($imageUrl);
        }

        // Delete from local storage
        return self::deleteFromLocal($imageUrl);
    }

    private static function deleteFromCloudinary($imageUrl) {
        try {
            // Extract public_id from URL
            // Example URL: https://res.cloudinary.com/cloud_name/image/upload/v1234567890/folder/filename.jpg
            $urlParts = explode('/upload/', $imageUrl);
            if (count($urlParts) === 2) {
                $pathParts = explode('/', $urlParts[1]);
                // Remove version number (v1234567890)
                if (preg_match('/^v\d+$/', $pathParts[0])) {
                    array_shift($pathParts);
                }
                
                // Reconstruct public_id (folder/filename without extension)
                $publicId = implode('/', $pathParts);
                $publicId = preg_replace('/\.[^.]+$/', '', $publicId); // Remove extension
                
                $result = self::$cloudinary->uploadApi()->destroy($publicId);
                
                return isset($result['result']) && $result['result'] === 'ok';
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Cloudinary delete error: " . $e->getMessage());
            return false;
        }
    }

    private static function deleteFromLocal($imageUrl) {
        // Extract filename from URL
        $filename = basename(parse_url($imageUrl, PHP_URL_PATH));
        $filepath = self::$uploadDir . '/products/' . $filename;
        
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        
        return false;
    }

    /**
     * Get optimized image URL with transformations
     * Useful for generating different sizes (thumbnails, etc.)
     */
    public static function getOptimizedUrl($imageUrl, $width = null, $height = null, $quality = 'auto') {
        if (!self::$useCloudinary || strpos($imageUrl, 'cloudinary.com') === false) {
            return $imageUrl; // Return original if not Cloudinary
        }

        // Build transformation string
        $transformations = [];
        if ($width) $transformations[] = "w_$width";
        if ($height) $transformations[] = "h_$height";
        if ($quality) $transformations[] = "q_$quality";
        $transformations[] = "f_auto"; // Auto format

        $transformStr = implode(',', $transformations);

        // Insert transformations into URL
        return str_replace('/upload/', "/upload/$transformStr/", $imageUrl);
    }
}

<?php
/**
 * Cloudinary Integration Test Script
 * 
 * This script tests if your Cloudinary configuration is working properly.
 * Run this locally before deploying to production.
 * 
 * Usage: php test_cloudinary.php
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/utils/FileUpload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "🧪 Cloudinary Integration Test\n";
echo "================================\n\n";

// Check if Cloudinary is enabled
$useCloudinary = filter_var($_ENV['USE_CLOUDINARY'] ?? 'false', FILTER_VALIDATE_BOOLEAN);

echo "USE_CLOUDINARY: " . ($useCloudinary ? '✅ true' : '❌ false') . "\n";

if (!$useCloudinary) {
    echo "\n⚠️  Cloudinary is disabled. Set USE_CLOUDINARY=true in .env to test.\n";
    echo "Local file upload will be used instead.\n\n";
    exit(0);
}

// Check Cloudinary credentials
echo "\n📋 Checking Cloudinary Credentials:\n";
echo "-----------------------------------\n";

$cloudName = $_ENV['CLOUDINARY_CLOUD_NAME'] ?? null;
$apiKey = $_ENV['CLOUDINARY_API_KEY'] ?? null;
$apiSecret = $_ENV['CLOUDINARY_API_SECRET'] ?? null;
$folder = $_ENV['CLOUDINARY_FOLDER'] ?? 'swordhub/products';

echo "Cloud Name: " . ($cloudName ? "✅ Set ($cloudName)" : "❌ Missing") . "\n";
echo "API Key: " . ($apiKey ? "✅ Set (" . substr($apiKey, 0, 6) . "...)" : "❌ Missing") . "\n";
echo "API Secret: " . ($apiSecret ? "✅ Set (***)" : "❌ Missing") . "\n";
echo "Folder: ✅ $folder\n";

if (!$cloudName || !$apiKey || !$apiSecret) {
    echo "\n❌ Missing Cloudinary credentials!\n";
    echo "Please set these in your .env file:\n";
    echo "  - CLOUDINARY_CLOUD_NAME\n";
    echo "  - CLOUDINARY_API_KEY\n";
    echo "  - CLOUDINARY_API_SECRET\n\n";
    exit(1);
}

// Test Cloudinary connection
echo "\n🔌 Testing Cloudinary Connection:\n";
echo "----------------------------------\n";

try {
    $cloudinary = new \Cloudinary\Cloudinary([
        'cloud' => [
            'cloud_name' => $cloudName,
            'api_key' => $apiKey,
            'api_secret' => $apiSecret
        ]
    ]);

    // Test by fetching account usage
    $usage = $cloudinary->adminApi()->usage();
    
    echo "✅ Connection successful!\n\n";
    echo "📊 Account Info:\n";
    echo "  - Plan: " . ($usage['plan'] ?? 'Unknown') . "\n";
    echo "  - Credits Used: " . ($usage['credits']['usage'] ?? 0) . " / " . ($usage['credits']['limit'] ?? 'Unlimited') . "\n";
    echo "  - Bandwidth Used: " . round(($usage['bandwidth']['usage'] ?? 0) / 1024 / 1024 / 1024, 2) . " GB\n";
    echo "  - Storage Used: " . round(($usage['storage']['usage'] ?? 0) / 1024 / 1024, 2) . " MB\n";
    echo "  - Resources: " . ($usage['resources'] ?? 0) . " files\n";
    
} catch (Exception $e) {
    echo "❌ Connection failed!\n";
    echo "Error: " . $e->getMessage() . "\n\n";
    echo "Please verify your credentials in Cloudinary dashboard.\n";
    exit(1);
}

echo "\n✅ All tests passed!\n";
echo "\n🎯 Next Steps:\n";
echo "  1. Your Cloudinary integration is working correctly\n";
echo "  2. When you deploy to Render, set USE_CLOUDINARY=true\n";
echo "  3. Add these same credentials to Render environment variables\n";
echo "  4. Test image upload via admin dashboard after deployment\n\n";

echo "💡 Tip: Images will be optimized automatically:\n";
echo "  - Max size: 1200x1200px\n";
echo "  - Quality: auto (optimized for web)\n";
echo "  - Format: auto (WebP for modern browsers)\n";
echo "  - Served via CDN for fast loading\n\n";

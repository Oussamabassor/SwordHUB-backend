<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/MongoDB.php';

echo "🔍 Verifying MongoDB Atlas Data...\n\n";

try {
    $mongodb = MongoDB::getInstance();
    $db = $mongodb->getDatabase();
    
    // Count collections
    $users = $db->selectCollection('users')->countDocuments();
    $products = $db->selectCollection('products')->countDocuments();
    $categories = $db->selectCollection('categories')->countDocuments();
    
    echo "📊 Database Statistics:\n";
    echo "  Users: $users\n";
    echo "  Products: $products\n";
    echo "  Categories: $categories\n\n";
    
    // Show categories
    echo "📁 Categories:\n";
    $categoriesData = $db->selectCollection('categories')->find();
    foreach ($categoriesData as $cat) {
        echo "  - {$cat['name']}: {$cat['description']}\n";
    }
    
    echo "\n✅ Database verification complete!\n";
    echo "\n🚀 Your backend is ready to use!\n";
    echo "\nAdmin Login:\n";
    echo "  Email: admin@swordhub.com\n";
    echo "  Password: Admin123!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

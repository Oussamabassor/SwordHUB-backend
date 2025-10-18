<?php
/**
 * Script to update existing orders with product images
 * Run this once to fix old orders that don't have image data
 */

require_once __DIR__ . '/config/MongoDB.php';
require_once __DIR__ . '/models/Product.php';

echo "Starting order image update...\n\n";

$db = MongoDB::getInstance();
$ordersCollection = $db->selectCollection('orders');
$productModel = new Product();

// Get all orders
$orders = $ordersCollection->find()->toArray();
echo "Found " . count($orders) . " orders to check\n\n";

$updatedCount = 0;
$errorCount = 0;

foreach ($orders as $order) {
    $orderId = (string)$order['_id'];
    $needsUpdate = false;
    $updatedItems = [];
    
    echo "Checking order: " . $orderId . "\n";
    
    foreach ($order['items'] as $item) {
        // Check if image is missing
        if (!isset($item['image']) || empty($item['image'])) {
            echo "  - Item '{$item['productName']}' missing image\n";
            $needsUpdate = true;
            
            // Fetch product to get image
            try {
                $product = $productModel->findById($item['productId']);
                if ($product && isset($product['image'])) {
                    $item['image'] = $product['image'];
                    echo "    ✓ Added image: {$product['image']}\n";
                } else {
                    $item['image'] = null;
                    echo "    ✗ Product not found or has no image\n";
                }
            } catch (Exception $e) {
                $item['image'] = null;
                echo "    ✗ Error: " . $e->getMessage() . "\n";
            }
        }
        
        // Also add size field if missing (for consistency)
        if (!isset($item['size'])) {
            $item['size'] = null;
        }
        
        $updatedItems[] = $item;
    }
    
    // Update order if needed
    if ($needsUpdate) {
        try {
            $result = $ordersCollection->updateOne(
                ['_id' => $order['_id']],
                ['$set' => ['items' => $updatedItems]]
            );
            
            if ($result->getModifiedCount() > 0) {
                echo "  ✓ Order updated successfully\n\n";
                $updatedCount++;
            } else {
                echo "  - No changes made\n\n";
            }
        } catch (Exception $e) {
            echo "  ✗ Error updating order: " . $e->getMessage() . "\n\n";
            $errorCount++;
        }
    } else {
        echo "  - All items already have images\n\n";
    }
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "Update complete!\n";
echo "Orders updated: $updatedCount\n";
echo "Errors: $errorCount\n";
echo str_repeat("=", 50) . "\n";

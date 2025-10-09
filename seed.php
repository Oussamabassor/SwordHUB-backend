<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/MongoDB.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Category.php';
require_once __DIR__ . '/models/Product.php';

echo "🌱 Starting database seed...\n\n";

try {
    // Initialize models
    $userModel = new User();
    $categoryModel = new Category();
    $productModel = new Product();

    // 1. Create Admin User
    echo "👤 Creating admin user...\n";
    try {
        $admin = $userModel->create([
            'name' => 'Admin User',
            'email' => 'admin@swordhub.com',
            'password' => 'Admin123!',
            'role' => 'admin'
        ]);
        echo "✅ Admin user created: admin@swordhub.com (Password: Admin123!)\n\n";
    } catch (Exception $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️  Admin user already exists\n\n";
        } else {
            throw $e;
        }
    }

    // 2. Create Categories
    echo "📁 Creating categories...\n";
    $categories = [];
    $categoryData = [
        ['name' => 'Training', 'description' => 'Training equipment and gear'],
        ['name' => 'Footwear', 'description' => 'Athletic shoes and boots'],
        ['name' => 'Accessories', 'description' => 'Sports accessories and gear'],
        ['name' => 'Apparel', 'description' => 'Athletic clothing and apparel']
    ];

    foreach ($categoryData as $catData) {
        try {
            $category = $categoryModel->create($catData);
            $categories[$catData['name']] = (string)$category['_id'];
            echo "  ✅ {$catData['name']}\n";
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'already exists') !== false) {
                echo "  ℹ️  {$catData['name']} already exists\n";
                // Find existing category
                $existing = $categoryModel->findAll();
                foreach ($existing as $cat) {
                    if ($cat['name'] === $catData['name']) {
                        $categories[$catData['name']] = (string)$cat['_id'];
                        break;
                    }
                }
            } else {
                throw $e;
            }
        }
    }
    echo "\n";

    // 3. Create Sample Products
    echo "🏷️  Creating sample products...\n";
    $products = [
        [
            'name' => 'Professional Training Sword',
            'description' => 'High-quality training sword perfect for martial arts and HEMA practice.',
            'price' => 89.99,
            'category' => $categories['Training'],
            'stock' => 50,
            'image' => 'https://via.placeholder.com/400x400?text=Training+Sword',
            'featured' => true
        ],
        [
            'name' => 'Competition Fencing Foil',
            'description' => 'Professional-grade fencing foil for competitive use.',
            'price' => 129.99,
            'category' => $categories['Training'],
            'stock' => 30,
            'image' => 'https://via.placeholder.com/400x400?text=Fencing+Foil',
            'featured' => true
        ],
        [
            'name' => 'Katana Practice Sword',
            'description' => 'Authentic replica katana for training and display.',
            'price' => 149.99,
            'category' => $categories['Training'],
            'stock' => 25,
            'image' => 'https://via.placeholder.com/400x400?text=Katana',
            'featured' => false
        ],
        [
            'name' => 'Medieval Longsword Trainer',
            'description' => 'Historically accurate longsword trainer with balanced weight.',
            'price' => 99.99,
            'category' => $categories['Training'],
            'stock' => 40,
            'image' => 'https://via.placeholder.com/400x400?text=Longsword',
            'featured' => true
        ],
        [
            'name' => 'Fencing Shoes Pro',
            'description' => 'Professional fencing shoes with excellent grip and support.',
            'price' => 79.99,
            'category' => $categories['Footwear'],
            'stock' => 60,
            'image' => 'https://via.placeholder.com/400x400?text=Fencing+Shoes',
            'featured' => false
        ],
        [
            'name' => 'Martial Arts Training Boots',
            'description' => 'Durable training boots for martial arts practice.',
            'price' => 64.99,
            'category' => $categories['Footwear'],
            'stock' => 45,
            'image' => 'https://via.placeholder.com/400x400?text=Training+Boots',
            'featured' => false
        ],
        [
            'name' => 'Protective Fencing Glove',
            'description' => 'High-quality protective glove for fencing and sword training.',
            'price' => 34.99,
            'category' => $categories['Accessories'],
            'stock' => 100,
            'image' => 'https://via.placeholder.com/400x400?text=Fencing+Glove',
            'featured' => false
        ],
        [
            'name' => 'Sword Maintenance Kit',
            'description' => 'Complete kit for maintaining and cleaning your practice swords.',
            'price' => 24.99,
            'category' => $categories['Accessories'],
            'stock' => 80,
            'image' => 'https://via.placeholder.com/400x400?text=Maintenance+Kit',
            'featured' => false
        ],
        [
            'name' => 'Fencing Mask Premium',
            'description' => 'Professional fencing mask with enhanced protection.',
            'price' => 89.99,
            'category' => $categories['Accessories'],
            'stock' => 35,
            'image' => 'https://via.placeholder.com/400x400?text=Fencing+Mask',
            'featured' => true
        ],
        [
            'name' => 'Training Jacket',
            'description' => 'Comfortable and durable jacket for training sessions.',
            'price' => 54.99,
            'category' => $categories['Apparel'],
            'stock' => 70,
            'image' => 'https://via.placeholder.com/400x400?text=Training+Jacket',
            'featured' => false
        ],
        [
            'name' => 'Fencing Pants Professional',
            'description' => 'Professional-grade fencing pants with reinforced knees.',
            'price' => 69.99,
            'category' => $categories['Apparel'],
            'stock' => 55,
            'image' => 'https://via.placeholder.com/400x400?text=Fencing+Pants',
            'featured' => false
        ],
        [
            'name' => 'Martial Arts Uniform Set',
            'description' => 'Complete martial arts uniform set including gi and belt.',
            'price' => 119.99,
            'category' => $categories['Apparel'],
            'stock' => 40,
            'image' => 'https://via.placeholder.com/400x400?text=Uniform+Set',
            'featured' => false
        ]
    ];

    foreach ($products as $productData) {
        try {
            $product = $productModel->create($productData);
            echo "  ✅ {$productData['name']}\n";
        } catch (Exception $e) {
            echo "  ❌ Failed to create {$productData['name']}: " . $e->getMessage() . "\n";
        }
    }
    echo "\n";

    echo "🎉 Database seeding completed successfully!\n\n";
    echo "📝 Summary:\n";
    echo "   - Admin Email: admin@swordhub.com\n";
    echo "   - Admin Password: Admin123!\n";
    echo "   - Categories: " . count($categoryData) . "\n";
    echo "   - Products: " . count($products) . "\n\n";
    echo "🚀 You can now start using the API!\n";

} catch (Exception $e) {
    echo "\n❌ Error during seeding: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}

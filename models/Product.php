<?php

require_once __DIR__ . '/../config/MongoDB.php';

class Product {
    private $collection;

    public function __construct() {
        try {
            error_log("Product model: Getting MongoDB instance...");
            $database = MongoDB::getInstance();
            error_log("Product model: MongoDB instance retrieved, type: " . get_class($database));
            
            error_log("Product model: Selecting products collection...");
            $this->collection = $database->selectCollection('products');
            error_log("Product model: Products collection selected successfully");
        } catch (Exception $e) {
            error_log("Product model __construct Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw new Exception("Failed to initialize Product model: " . $e->getMessage());
        }
    }

    public function create($data) {
        $product = [
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => (float)$data['price'],
            'category' => $data['category'],
            'stock' => (int)$data['stock'],
            'image' => $data['image'] ?? '',
            'images' => isset($data['images']) ? (array)$data['images'] : [],
            'sizes' => isset($data['sizes']) ? (array)$data['sizes'] : [],
            'featured' => isset($data['featured']) ? (bool)$data['featured'] : false,
            'createdAt' => new MongoDB\BSON\UTCDateTime(),
            'updatedAt' => new MongoDB\BSON\UTCDateTime()
        ];

        $result = $this->collection->insertOne($product);
        $product['_id'] = $result->getInsertedId();
        return $product;
    }

    public function findAll($filters = []) {
        $query = [];
        $options = ['sort' => ['createdAt' => -1]];

        // Apply filters
        if (isset($filters['category']) && !empty($filters['category'])) {
            $query['category'] = $filters['category'];
        }

        if (isset($filters['search']) && !empty($filters['search'])) {
            $query['name'] = new MongoDB\BSON\Regex($filters['search'], 'i');
        }

        if (isset($filters['minPrice']) || isset($filters['maxPrice'])) {
            $query['price'] = [];
            if (isset($filters['minPrice'])) {
                $query['price']['$gte'] = (float)$filters['minPrice'];
            }
            if (isset($filters['maxPrice'])) {
                $query['price']['$lte'] = (float)$filters['maxPrice'];
            }
        }

        if (isset($filters['featured'])) {
            $query['featured'] = filter_var($filters['featured'], FILTER_VALIDATE_BOOLEAN);
        }

        // Pagination
        $page = isset($filters['page']) ? max(1, (int)$filters['page']) : 1;
        $limit = isset($filters['limit']) ? max(1, min(100, (int)$filters['limit'])) : 10;
        $skip = ($page - 1) * $limit;

        $options['limit'] = $limit;
        $options['skip'] = $skip;

        $products = $this->collection->find($query, $options)->toArray();
        $total = $this->collection->countDocuments($query);
        $pages = ceil($total / $limit);

        return [
            'products' => $products,
            'total' => $total,
            'page' => $page,
            'pages' => $pages
        ];
    }

    public function findById($id) {
        try {
            return $this->collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
        } catch (Exception $e) {
            return null;
        }
    }

    public function findByCategory($categoryId) {
        return $this->collection->find(['category' => $categoryId])->toArray();
    }

    public function countByCategory($categoryId) {
        return $this->collection->countDocuments(['category' => $categoryId]);
    }

    public function update($id, $data) {
        $updateData = [
            'updatedAt' => new MongoDB\BSON\UTCDateTime()
        ];

        if (isset($data['name'])) $updateData['name'] = $data['name'];
        if (isset($data['description'])) $updateData['description'] = $data['description'];
        if (isset($data['price'])) $updateData['price'] = (float)$data['price'];
        if (isset($data['category'])) $updateData['category'] = $data['category'];
        if (isset($data['stock'])) {
            $updateData['stock'] = (int)$data['stock'];
            // If stock is set to 0, automatically set featured to false
            if ((int)$data['stock'] === 0) {
                $updateData['featured'] = false;
            }
        }
        if (isset($data['image'])) $updateData['image'] = $data['image'];
        if (isset($data['images'])) $updateData['images'] = (array)$data['images'];
        if (isset($data['sizes'])) $updateData['sizes'] = (array)$data['sizes'];
        if (isset($data['featured'])) $updateData['featured'] = (bool)$data['featured'];

        try {
            $result = $this->collection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($id)],
                ['$set' => $updateData]
            );
            return $result->getModifiedCount() > 0 || $result->getMatchedCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateStock($id, $quantity) {
        try {
            // Decrement stock
            $result = $this->collection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($id)],
                ['$inc' => ['stock' => -$quantity]]
            );
            
            // Check if stock is now 0 or negative, and update featured to false
            if ($result->getModifiedCount() > 0 || $result->getMatchedCount() > 0) {
                $product = $this->findById($id);
                if ($product && $product['stock'] <= 0) {
                    // Set stock to 0 if it went negative and set featured to false
                    $this->collection->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($id)],
                        ['$set' => [
                            'stock' => 0,
                            'featured' => false
                        ]]
                    );
                }
            }
            
            return $result->getModifiedCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($id) {
        try {
            $result = $this->collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
            return $result->getDeletedCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    public function count() {
        return $this->collection->countDocuments([]);
    }
}


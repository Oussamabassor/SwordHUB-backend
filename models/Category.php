<?php

require_once __DIR__ . '/../config/MongoDB.php';

class Category {
    private $collection;

    public function __construct() {
        $db = MongoDB::getInstance();
        $this->collection = $db->selectCollection('categories');
        
        // Create unique index on name
        try {
            $this->collection->createIndex(['name' => 1], ['unique' => true]);
        } catch (Exception $e) {
            // Index might already exist
        }
    }

    public function create($data) {
        $category = [
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'createdAt' => new MongoDB\BSON\UTCDateTime(),
            'updatedAt' => new MongoDB\BSON\UTCDateTime()
        ];

        try {
            $result = $this->collection->insertOne($category);
            $category['_id'] = $result->getInsertedId();
            return $category;
        } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
            if (strpos($e->getMessage(), 'duplicate key') !== false) {
                throw new Exception("Category name already exists");
            }
            throw $e;
        }
    }

    public function findAll() {
        $categories = $this->collection->find([], ['sort' => ['name' => 1]])->toArray();
        
        // Add product count to each category
        $productModel = new Product();
        foreach ($categories as &$category) {
            $category['productCount'] = $productModel->countByCategory((string)$category['_id']);
        }
        
        return $categories;
    }

    public function findById($id) {
        try {
            $category = $this->collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
            if ($category) {
                // Get products in this category
                $productModel = new Product();
                $category['products'] = $productModel->findByCategory((string)$category['_id']);
            }
            return $category;
        } catch (Exception $e) {
            return null;
        }
    }

    public function update($id, $data) {
        $updateData = [
            'updatedAt' => new MongoDB\BSON\UTCDateTime()
        ];

        if (isset($data['name'])) $updateData['name'] = $data['name'];
        if (isset($data['description'])) $updateData['description'] = $data['description'];

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

    public function delete($id) {
        try {
            // Check if category has products
            $productModel = new Product();
            if ($productModel->countByCategory($id) > 0) {
                throw new Exception("Cannot delete category with existing products");
            }
            
            $result = $this->collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
            return $result->getDeletedCount() > 0;
        } catch (Exception $e) {
            throw $e;
        }
    }
}

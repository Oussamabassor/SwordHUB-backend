<?php

require_once __DIR__ . '/../config/MongoDB.php';

class Order {
    private $collection;

    public function __construct() {
        $db = MongoDB::getInstance();
        $this->collection = $db->getCollection('orders');
    }

    public function create($data) {
        // Calculate total based on items
        $total = 0;
        $productModel = new Product();
        
        $items = [];
        foreach ($data['items'] as $item) {
            $product = $productModel->findById($item['productId']);
            
            if (!$product) {
                throw new Exception("Product not found: " . $item['productId']);
            }
            
            if ($product['stock'] < $item['quantity']) {
                throw new Exception("Insufficient stock for product: " . $product['name']);
            }
            
            $itemTotal = $product['price'] * $item['quantity'];
            $total += $itemTotal;
            
            $items[] = [
                'productId' => $item['productId'],
                'productName' => $product['name'],
                'quantity' => (int)$item['quantity'],
                'price' => (float)$product['price']
            ];
            
            // Reduce stock
            $productModel->updateStock($item['productId'], $item['quantity']);
        }

        $order = [
            'customerName' => $data['customerName'],
            'customerEmail' => $data['customerEmail'],
            'customerPhone' => $data['customerPhone'] ?? '',
            'customerAddress' => $data['customerAddress'] ?? '',
            'items' => $items,
            'total' => (float)$total,
            'status' => 'pending',
            'createdAt' => new MongoDB\BSON\UTCDateTime(),
            'updatedAt' => new MongoDB\BSON\UTCDateTime()
        ];

        $result = $this->collection->insertOne($order);
        $order['_id'] = $result->getInsertedId();
        return $order;
    }

    public function findAll($filters = []) {
        $query = [];
        $options = ['sort' => ['createdAt' => -1]];

        // Apply filters
        if (isset($filters['status']) && !empty($filters['status'])) {
            $query['status'] = $filters['status'];
        }

        if (isset($filters['startDate'])) {
            $query['createdAt']['$gte'] = new MongoDB\BSON\UTCDateTime(strtotime($filters['startDate']) * 1000);
        }

        if (isset($filters['endDate'])) {
            $query['createdAt']['$lte'] = new MongoDB\BSON\UTCDateTime(strtotime($filters['endDate']) * 1000);
        }

        return $this->collection->find($query, $options)->toArray();
    }

    public function findById($id) {
        try {
            return $this->collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
        } catch (Exception $e) {
            return null;
        }
    }

    public function updateStatus($id, $status) {
        $allowedStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        
        if (!in_array($status, $allowedStatuses)) {
            throw new Exception("Invalid status");
        }

        try {
            $result = $this->collection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($id)],
                ['$set' => [
                    'status' => $status,
                    'updatedAt' => new MongoDB\BSON\UTCDateTime()
                ]]
            );
            return $result->getModifiedCount() > 0 || $result->getMatchedCount() > 0;
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

    public function getStats() {
        $pipeline = [
            [
                '$group' => [
                    '_id' => null,
                    'totalOrders' => ['$sum' => 1],
                    'totalRevenue' => ['$sum' => '$total'],
                    'pendingOrders' => [
                        '$sum' => ['$cond' => [['$eq' => ['$status', 'pending']], 1, 0]]
                    ]
                ]
            ]
        ];

        $result = $this->collection->aggregate($pipeline)->toArray();
        
        if (empty($result)) {
            return [
                'totalOrders' => 0,
                'pendingOrders' => 0,
                'totalRevenue' => 0
            ];
        }

        return [
            'totalOrders' => $result[0]['totalOrders'],
            'pendingOrders' => $result[0]['pendingOrders'],
            'totalRevenue' => round($result[0]['totalRevenue'], 2)
        ];
    }

    public function count() {
        return $this->collection->countDocuments([]);
    }

    public function getRecentOrders($limit = 5) {
        return $this->collection->find([], [
            'sort' => ['createdAt' => -1],
            'limit' => $limit
        ])->toArray();
    }

    public function getAnalytics($period = 'month') {
        $dateFormat = '%Y-%m-%d';
        $startDate = null;

        switch ($period) {
            case 'day':
                $startDate = strtotime('-7 days');
                $dateFormat = '%Y-%m-%d';
                break;
            case 'week':
                $startDate = strtotime('-8 weeks');
                $dateFormat = '%Y-W%U';
                break;
            case 'month':
                $startDate = strtotime('-12 months');
                $dateFormat = '%Y-%m';
                break;
            case 'year':
                $startDate = strtotime('-5 years');
                $dateFormat = '%Y';
                break;
            default:
                $startDate = strtotime('-12 months');
        }

        $pipeline = [
            [
                '$match' => [
                    'createdAt' => ['$gte' => new MongoDB\BSON\UTCDateTime($startDate * 1000)]
                ]
            ],
            [
                '$group' => [
                    '_id' => ['$dateToString' => ['format' => $dateFormat, 'date' => '$createdAt']],
                    'totalSales' => ['$sum' => '$total'],
                    'orderCount' => ['$sum' => 1]
                ]
            ],
            [
                '$sort' => ['_id' => 1]
            ]
        ];

        return $this->collection->aggregate($pipeline)->toArray();
    }
}

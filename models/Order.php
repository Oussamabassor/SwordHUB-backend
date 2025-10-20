<?php

require_once __DIR__ . '/../config/MongoDB.php';

class Order {
    private $collection;

    public function __construct() {
        $database = MongoDB::getInstance();
        $this->collection = $database->selectCollection('orders');
    }

    public function create($data) {
        // Calculate total based on items
        $total = 0;
        $productModel = new Product();
        
        // Log incoming data for debugging
        error_log("Creating order with data: " . json_encode($data));
        
        $items = [];
        foreach ($data['items'] as $item) {
            error_log("Looking up product with ID: " . $item['productId']);
            $product = $productModel->findById($item['productId']);
            
            if (!$product) {
                error_log("Product not found for ID: " . $item['productId']);
                throw new Exception("Product not found: " . $item['productId']);
            }
            
            error_log("Product found: " . json_encode($product));
            
            if ($product['stock'] < $item['quantity']) {
                throw new Exception("Insufficient stock for product: " . $product['name']);
            }
            
            $itemTotal = $product['price'] * $item['quantity'];
            $total += $itemTotal;
            
            $items[] = [
                'productId' => $item['productId'],
                'productName' => $product['name'],
                'quantity' => (int)$item['quantity'],
                'price' => (float)$product['price'],
                'image' => (isset($product['images']) && is_array($product['images']) && count($product['images']) > 0) 
                    ? $product['images'][0] 
                    : ($product['image'] ?? null),  // Use first image from images array, fallback to image field
                'size' => $item['size'] ?? null  // Include size if provided
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

        // Pagination
        $page = isset($filters['page']) ? max(1, (int)$filters['page']) : 1;
        $limit = isset($filters['limit']) ? max(1, min(100, (int)$filters['limit'])) : 50;
        $skip = ($page - 1) * $limit;

        $options['limit'] = $limit;
        $options['skip'] = $skip;

        $orders = $this->collection->find($query, $options)->toArray();
        $total = $this->collection->countDocuments($query);
        $pages = ceil($total / $limit);

        return [
            'orders' => $orders,
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
        
        // Count unique clients by phone number
        $uniqueClientsPipeline = [
            [
                '$match' => [
                    'customerPhone' => ['$ne' => '', '$exists' => true]
                ]
            ],
            [
                '$group' => [
                    '_id' => '$customerPhone'
                ]
            ],
            [
                '$count' => 'totalClients'
            ]
        ];
        
        $clientsResult = $this->collection->aggregate($uniqueClientsPipeline)->toArray();
        $totalClients = !empty($clientsResult) ? $clientsResult[0]['totalClients'] : 0;
        
        if (empty($result)) {
            return [
                'totalOrders' => 0,
                'totalClients' => $totalClients,
                'totalRevenue' => 0
            ];
        }

        return [
            'totalOrders' => $result[0]['totalOrders'],
            'totalClients' => $totalClients,
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

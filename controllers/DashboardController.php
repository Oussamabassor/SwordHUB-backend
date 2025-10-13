<?php

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Category.php';

class DashboardController {
    private $productModel;
    private $orderModel;
    private $categoryModel;

    public function __construct() {
        $this->productModel = new Product();
        $this->orderModel = new Order();
        $this->categoryModel = new Category();
    }

    public function getStats() {
        $orderStats = $this->orderModel->getStats();
        $recentOrders = $this->orderModel->getRecentOrders(5);

        // Convert ObjectIds and dates to strings for recent orders
        foreach ($recentOrders as &$order) {
            $order['id'] = (string)$order['_id'];
            unset($order['_id']);
            
            // Convert MongoDB UTCDateTime to ISO string
            if (isset($order['createdAt']) && $order['createdAt'] instanceof MongoDB\BSON\UTCDateTime) {
                $order['createdAt'] = $order['createdAt']->toDateTime()->format('c');
            }
            if (isset($order['updatedAt']) && $order['updatedAt'] instanceof MongoDB\BSON\UTCDateTime) {
                $order['updatedAt'] = $order['updatedAt']->toDateTime()->format('c');
            }
        }

        $stats = [
            'totalProducts' => $this->productModel->count(),
            'totalOrders' => $orderStats['totalOrders'],
            'totalRevenue' => $orderStats['totalRevenue'],
            'pendingOrders' => $orderStats['pendingOrders'],
            'totalCategories' => count($this->categoryModel->findAll()),
            'recentOrders' => $recentOrders
        ];

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function getAnalytics() {
        $period = $_GET['period'] ?? 'month';

        $allowedPeriods = ['day', 'week', 'month', 'year'];
        if (!in_array($period, $allowedPeriods)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Invalid period. Allowed values: ' . implode(', ', $allowedPeriods)
            ]);
            return;
        }

        $analytics = $this->orderModel->getAnalytics($period);

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $analytics
        ]);
    }
}

<?php

require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../utils/Validator.php';

class OrderController {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new Order();
    }

    public function getAll() {
        $filters = [
            'status' => $_GET['status'] ?? null,
            'startDate' => $_GET['startDate'] ?? null,
            'endDate' => $_GET['endDate'] ?? null
        ];

        $orders = $this->orderModel->findAll($filters);

        // Convert MongoDB ObjectIds to strings
        foreach ($orders as &$order) {
            $order['id'] = (string)$order['_id'];
            unset($order['_id']);
        }

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function getById($id) {
        $order = $this->orderModel->findById($id);

        if (!$order) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Order not found'
            ]);
            return;
        }

        $order['id'] = (string)$order['_id'];
        unset($order['_id']);

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $order
        ]);
    }

    public function create() {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate input
        $errors = Validator::validateRequired($data, ['customerName', 'customerEmail', 'items']);
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ]);
            return;
        }

        // Validate email
        $emailError = Validator::validateEmail($data['customerEmail']);
        if ($emailError) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $emailError
            ]);
            return;
        }

        // Validate items array
        if (empty($data['items']) || !is_array($data['items'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Order must contain at least one item'
            ]);
            return;
        }

        try {
            $order = $this->orderModel->create([
                'customerName' => Validator::sanitizeString($data['customerName']),
                'customerEmail' => Validator::sanitizeString($data['customerEmail']),
                'customerPhone' => isset($data['customerPhone']) ? Validator::sanitizeString($data['customerPhone']) : '',
                'customerAddress' => isset($data['customerAddress']) ? Validator::sanitizeString($data['customerAddress']) : '',
                'items' => $data['items']
            ]);

            $order['id'] = (string)$order['_id'];
            unset($order['_id']);

            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $order
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateStatus($id) {
        $order = $this->orderModel->findById($id);
        if (!$order) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Order not found'
            ]);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['status'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Status is required'
            ]);
            return;
        }

        try {
            $this->orderModel->updateStatus($id, $data['status']);

            $updatedOrder = $this->orderModel->findById($id);
            $updatedOrder['id'] = (string)$updatedOrder['_id'];
            unset($updatedOrder['_id']);

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Order status updated successfully',
                'data' => $updatedOrder
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete($id) {
        $order = $this->orderModel->findById($id);
        if (!$order) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Order not found'
            ]);
            return;
        }

        try {
            $this->orderModel->delete($id);

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Order deleted successfully'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getStats() {
        $stats = $this->orderModel->getStats();

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $stats
        ]);
    }
}

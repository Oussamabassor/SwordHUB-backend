<?php

require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../utils/Validator.php';

class CategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new Category();
    }

    public function getAll() {
        $categories = $this->categoryModel->findAll();

        // Convert MongoDB ObjectIds to strings
        foreach ($categories as &$category) {
            $category['id'] = (string)$category['_id'];
            unset($category['_id']);
        }

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => [
                'categories' => $categories,
                'total' => count($categories)
            ]
        ]);
    }

    public function getById($id) {
        $category = $this->categoryModel->findById($id);

        if (!$category) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Category not found'
            ]);
            return;
        }

        $category['id'] = (string)$category['_id'];
        unset($category['_id']);

        // Convert product IDs
        if (isset($category['products'])) {
            foreach ($category['products'] as &$product) {
                $product['id'] = (string)$product['_id'];
                unset($product['_id']);
            }
        }

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $category
        ]);
    }

    public function create() {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate input
        $errors = Validator::validateRequired($data, ['name']);
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ]);
            return;
        }

        try {
            $category = $this->categoryModel->create([
                'name' => Validator::sanitizeString($data['name']),
                'description' => isset($data['description']) ? Validator::sanitizeString($data['description']) : ''
            ]);

            $category['id'] = (string)$category['_id'];
            unset($category['_id']);

            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => $category
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update($id) {
        $category = $this->categoryModel->findById($id);
        if (!$category) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Category not found'
            ]);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        try {
            $updateData = [];
            if (isset($data['name'])) $updateData['name'] = Validator::sanitizeString($data['name']);
            if (isset($data['description'])) $updateData['description'] = Validator::sanitizeString($data['description']);

            $this->categoryModel->update($id, $updateData);

            $updatedCategory = $this->categoryModel->findById($id);
            $updatedCategory['id'] = (string)$updatedCategory['_id'];
            unset($updatedCategory['_id']);

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => $updatedCategory
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete($id) {
        $category = $this->categoryModel->findById($id);
        if (!$category) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Category not found'
            ]);
            return;
        }

        try {
            $this->categoryModel->delete($id);

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}

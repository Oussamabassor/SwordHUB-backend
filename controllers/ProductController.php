<?php

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../utils/Validator.php';
require_once __DIR__ . '/../utils/FileUpload.php';

class ProductController {
    private $productModel;
    private $categoryModel;
    private $categoriesCache = null;

    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }

    private function loadCategoriesCache() {
        if ($this->categoriesCache === null) {
            $categories = $this->categoryModel->findAll();
            $this->categoriesCache = [];
            foreach ($categories as $category) {
                $this->categoriesCache[(string)$category['_id']] = $category['name'];
            }
        }
    }

    private function populateCategoryName(&$product) {
        if (isset($product['category'])) {
            $this->loadCategoriesCache();
            if (isset($this->categoriesCache[$product['category']])) {
                $product['categoryId'] = $product['category'];
                $product['category'] = $this->categoriesCache[$product['category']];
            }
        }
    }

    public function getAll() {
        try {
            $filters = [
                'category' => $_GET['category'] ?? null,
                'search' => $_GET['search'] ?? null,
                'minPrice' => $_GET['minPrice'] ?? null,
                'maxPrice' => $_GET['maxPrice'] ?? null,
                'featured' => $_GET['featured'] ?? null,
                'page' => $_GET['page'] ?? 1,
                'limit' => $_GET['limit'] ?? 10
            ];

            $result = $this->productModel->findAll($filters);

            // Convert MongoDB ObjectIds to strings and populate category names
            foreach ($result['products'] as &$product) {
                $product['id'] = (string)$product['_id'];
                unset($product['_id']);
                $this->populateCategoryName($product);
            }

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'data' => $result
            ]);
        } catch (Exception $e) {
            error_log("ProductController::getAll Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to fetch products',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getById($id) {
        $product = $this->productModel->findById($id);

        if (!$product) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Product not found'
            ]);
            return;
        }

        $product['id'] = (string)$product['_id'];
        unset($product['_id']);
        $this->populateCategoryName($product);

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $product
        ]);
    }

    public function create() {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate input
        $errors = Validator::validateRequired($data, ['name', 'description', 'price', 'category', 'stock']);
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ]);
            return;
        }

        // Validate price
        $priceError = Validator::validateNumber($data['price'], 0);
        if ($priceError) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Price ' . $priceError
            ]);
            return;
        }

        // Validate stock
        $stockError = Validator::validateNumber($data['stock'], 0);
        if ($stockError) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Stock ' . $stockError
            ]);
            return;
        }

        try {
            $product = $this->productModel->create([
                'name' => Validator::sanitizeString($data['name']),
                'description' => Validator::sanitizeString($data['description']),
                'price' => $data['price'],
                'category' => Validator::sanitizeString($data['category']),
                'stock' => $data['stock'],
                'image' => $data['image'] ?? '',
                'featured' => $data['featured'] ?? false
            ]);

            $product['id'] = (string)$product['_id'];
            unset($product['_id']);

            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update($id) {
        $product = $this->productModel->findById($id);
        if (!$product) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Product not found'
            ]);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        // Validate price if provided
        if (isset($data['price'])) {
            $priceError = Validator::validateNumber($data['price'], 0);
            if ($priceError) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Price ' . $priceError
                ]);
                return;
            }
        }

        // Validate stock if provided
        if (isset($data['stock'])) {
            $stockError = Validator::validateNumber($data['stock'], 0);
            if ($stockError) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Stock ' . $stockError
                ]);
                return;
            }
        }

        try {
            $updateData = [];
            if (isset($data['name'])) $updateData['name'] = Validator::sanitizeString($data['name']);
            if (isset($data['description'])) $updateData['description'] = Validator::sanitizeString($data['description']);
            if (isset($data['price'])) $updateData['price'] = $data['price'];
            if (isset($data['category'])) $updateData['category'] = Validator::sanitizeString($data['category']);
            if (isset($data['stock'])) $updateData['stock'] = $data['stock'];
            if (isset($data['image'])) $updateData['image'] = $data['image'];
            if (isset($data['featured'])) $updateData['featured'] = $data['featured'];

            $this->productModel->update($id, $updateData);

            $updatedProduct = $this->productModel->findById($id);
            $updatedProduct['id'] = (string)$updatedProduct['_id'];
            unset($updatedProduct['_id']);

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $updatedProduct
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
        $product = $this->productModel->findById($id);
        if (!$product) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Product not found'
            ]);
            return;
        }

        try {
            // Delete product image if exists
            if (!empty($product['image'])) {
                FileUpload::deleteImage($product['image']);
            }

            $this->productModel->delete($id);

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function upload() {
        if (!isset($_FILES['image'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'No file uploaded'
            ]);
            return;
        }

        $result = FileUpload::uploadImage($_FILES['image']);

        if ($result['success']) {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'imageUrl' => $result['imageUrl']
            ]);
        } else {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $result['message']
            ]);
        }
    }
}

-- =====================================================
-- LEGACY SQL SCHEMA - NO LONGER USED
-- =====================================================
-- This file is kept for reference only.
-- The application now uses MongoDB instead of MySQL.
-- 
-- MongoDB Collections are created automatically:
-- - users
-- - products
-- - categories
-- - orders
--
-- To seed the MongoDB database, run: php seed.php
-- =====================================================

-- Original MySQL Schema (deprecated)
CREATE DATABASE IF NOT EXISTS ecommerce_db;

USE ecommerce_db;

CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    original_price DECIMAL(10, 2),
    category VARCHAR(100),
    material VARCHAR(100),
    fit VARCHAR(100),
    image VARCHAR(255),
    is_new BOOLEAN DEFAULT false,
    rating DECIMAL(3, 2),
    reviews_count INT DEFAULT 0,
    features JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- MongoDB Collections Schema (current):
-- =====================================================
-- 
-- users: {
--   _id: ObjectId,
--   name: String,
--   email: String (unique),
--   password: String (hashed),
--   role: String (admin/customer),
--   createdAt: Date,
--   updatedAt: Date
-- }
--
-- products: {
--   _id: ObjectId,
--   name: String,
--   description: String,
--   price: Number,
--   category: String (category_id),
--   stock: Number,
--   image: String,
--   featured: Boolean,
--   createdAt: Date,
--   updatedAt: Date
-- }
--
-- categories: {
--   _id: ObjectId,
--   name: String (unique),
--   description: String,
--   createdAt: Date,
--   updatedAt: Date
-- }
--
-- orders: {
--   _id: ObjectId,
--   customerName: String,
--   customerEmail: String,
--   customerPhone: String,
--   customerAddress: String,
--   items: Array[{
--     productId: String,
--     productName: String,
--     quantity: Number,
--     price: Number
--   }],
--   total: Number,
--   status: String (pending/processing/shipped/delivered/cancelled),
--   createdAt: Date,
--   updatedAt: Date
-- }

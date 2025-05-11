<?php
// functions/products.php

require_once __DIR__ . '/../config/db.php';

/**
 * Get all products with pagination
 */
function getProducts($page = 1, $perPage = 10) {
    global $conn;
    
    $offset = ($page - 1) * $perPage;
    
    $stmt = $conn->prepare("SELECT * FROM products LIMIT ?, ?");
    $stmt->bind_param("ii", $offset, $perPage);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    
    // Get total count for pagination
    $total = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
    $totalPages = ceil($total / $perPage);
    
    return [
        'products' => $products,
        'totalPages' => $totalPages,
        'currentPage' => $page
    ];
}

/**
 * Add a new product
 */
function addProduct($data, $imageFile = null) {
    global $conn;
    
    // Handle image upload
    $imagePath = null;
    if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $ext = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
        $filename = 'product_' . time() . '_' . uniqid() . '.' . $ext;
        $uploadPath = $uploadDir . $filename;
        
        if (move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
            $imagePath = 'uploads/products/' . $filename;
        }
    }
    
    $stmt = $conn->prepare("
        INSERT INTO products 
        (title, slug, description, price, quantity, image, category_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->bind_param(
        "sssdisi",
        $data['title'],
        $data['slug'],
        $data['description'],
        $data['price'],
        $data['quantity'],
        $imagePath,
        $data['category_id']
    );
    
    return $stmt->execute();
}

/**
 * Delete a product
 */
function deleteProduct($id) {
    global $conn;
    
    // First get the product to delete its image
    $product = $conn->query("SELECT image FROM products WHERE id = $id")->fetch_assoc();
    
    if ($product && $product['image']) {
        $imagePath = __DIR__ . '/../' . $product['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
    
    return $conn->query("DELETE FROM products WHERE id = $id");
}

/**
 * Get a single product by ID
 */
function getProductById($id) {
    global $conn;
    $result = $conn->query("SELECT * FROM products WHERE id = $id");
    return $result->fetch_assoc();
}

/**
 * Update a product
 */
function updateProduct($id, $data, $imageFile = null) {
    global $conn;
    
    $product = getProductById($id);
    $imagePath = $product['image'];
    
    // Handle new image upload
    if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
        // Delete old image if exists
        if ($imagePath) {
            $oldImagePath = __DIR__ . '/../' . $imagePath;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        
        // Upload new image
        $uploadDir = __DIR__ . '/../uploads/products/';
        $ext = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
        $filename = 'product_' . time() . '_' . uniqid() . '.' . $ext;
        $uploadPath = $uploadDir . $filename;
        
        if (move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
            $imagePath = 'uploads/products/' . $filename;
        }
    }
    
    $stmt = $conn->prepare("
        UPDATE products SET 
        title = ?, 
        slug = ?, 
        description = ?, 
        price = ?, 
        quantity = ?, 
        image = ?, 
        category_id = ? 
        WHERE id = ?
    ");
    
    $stmt->bind_param(
        "sssdisii",
        $data['title'],
        $data['slug'],
        $data['description'],
        $data['price'],
        $data['quantity'],
        $imagePath,
        $data['category_id'],
        $id
    );
    
    return $stmt->execute();
}
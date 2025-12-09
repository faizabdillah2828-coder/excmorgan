<?php
// config/functions.php

require_once 'database.php';

// Function to get all active products
function getProducts($limit = null) {
    global $pdo;
    $sql = "SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC";
    if ($limit) {
        $sql .= " LIMIT :limit";
    }
    $stmt = $pdo->prepare($sql);
    if ($limit) {
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get product by ID
function getProductById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to get all products
function getAllProducts() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to upload product image
function uploadProductImage($file) {
    if ($file && $file['error'] == 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (in_array($file_extension, $allowed_extensions)) {
            $max_size = 5 * 1024 * 1024; // 5MB
            if ($file['size'] <= $max_size) {
                $new_filename = 'product_' . time() . '_' . uniqid() . '.' . $file_extension;
                $target_dir = __DIR__ . '/../img/';

                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                $target_path = $target_dir . $new_filename;

                if (move_uploaded_file($file['tmp_name'], $target_path)) {
                    return 'img/' . $new_filename;
                }
            }
        }
    }
    return false; // Upload failed
}

// Function to delete product image file
function deleteProductImage($image_path) {
    if ($image_path && strpos($image_path, 'placeholder') === false) {
        $full_path = __DIR__ . '/../' . $image_path;
        if (file_exists($full_path)) {
            return unlink($full_path);
        }
    }
    return true; // Return true if no need to delete or delete successful
}

// Function to add product to cart
function addProductToCart($user_id, $product_id, $quantity = 1) {
    global $pdo;

    // Get cart for user
    $cart_stmt = $pdo->prepare("SELECT id FROM carts WHERE user_id = :user_id");
    $cart_stmt->bindParam(':user_id', $user_id);
    $cart_stmt->execute();
    $cart = $cart_stmt->fetch();

    if (!$cart) {
        // Create cart if doesn't exist
        $create_cart_stmt = $pdo->prepare("INSERT INTO carts (user_id) VALUES (:user_id)");
        $create_cart_stmt->bindParam(':user_id', $user_id);
        $create_cart_stmt->execute();
        $cart_id = $pdo->lastInsertId();
    } else {
        $cart_id = $cart['id'];
    }

    // Check if product already in cart
    $check_stmt = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id");
    $check_stmt->bindParam(':cart_id', $cart_id);
    $check_stmt->bindParam(':product_id', $product_id);
    $check_stmt->execute();
    $existing_item = $check_stmt->fetch();

    if ($existing_item) {
        // Update quantity
        $new_quantity = $existing_item['quantity'] + $quantity;
        $update_stmt = $pdo->prepare("UPDATE cart_items SET quantity = :quantity WHERE id = :id");
        $update_stmt->bindParam(':quantity', $new_quantity);
        $update_stmt->bindParam(':id', $existing_item['id']);
        $update_stmt->execute();
    } else {
        // Insert new item
        $insert_stmt = $pdo->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)");
        $insert_stmt->bindParam(':cart_id', $cart_id);
        $insert_stmt->bindParam(':product_id', $product_id);
        $insert_stmt->bindParam(':quantity', $quantity);
        $insert_stmt->execute();
    }
}

// Function to get cart items for user
function getCartItems($user_id) {
    global $pdo;
    $sql = "SELECT ci.id as cart_item_id, ci.quantity, p.* 
            FROM cart_items ci 
            JOIN carts c ON ci.cart_id = c.id 
            JOIN products p ON ci.product_id = p.id 
            WHERE c.user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get site settings
function getSiteSettings() {
    global $pdo;
    $stmt = $pdo->query("SELECT key_name, value FROM site_settings");
    $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    return $settings;
}

// Function to update cart item quantity
function updateCartItemQuantity($cart_item_id, $quantity) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE cart_items SET quantity = :quantity WHERE id = :id");
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':id', $cart_item_id);
    return $stmt->execute();
}

// Function to remove item from cart
function removeCartItem($cart_item_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE id = :id");
    $stmt->bindParam(':id', $cart_item_id);
    return $stmt->execute();
}

// Function to clear cart
function clearCart($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        DELETE ci FROM cart_items ci 
        JOIN carts c ON ci.cart_id = c.id 
        WHERE c.user_id = :user_id
    ");
    $stmt->bindParam(':user_id', $user_id);
    return $stmt->execute();
}

// Function to create order
function createOrder($user_id, $shipping_address, $payment_method) {
    global $pdo;
    
    // Get cart items
    $cart_items = getCartItems($user_id);
    
    if (empty($cart_items)) {
        return false;
    }
    
    // Calculate total
    $total_amount = 0;
    foreach ($cart_items as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }
    
    // Begin transaction
    $pdo->beginTransaction();
    
    try {
        // Insert order
        $order_stmt = $pdo->prepare("
            INSERT INTO orders (user_id, total_amount, shipping_address, payment_method) 
            VALUES (:user_id, :total_amount, :shipping_address, :payment_method)
        ");
        $order_stmt->bindParam(':user_id', $user_id);
        $order_stmt->bindParam(':total_amount', $total_amount);
        $order_stmt->bindParam(':shipping_address', $shipping_address);
        $order_stmt->bindParam(':payment_method', $payment_method);
        $order_stmt->execute();
        $order_id = $pdo->lastInsertId();
        
        // Insert order items
        foreach ($cart_items as $item) {
            $order_item_stmt = $pdo->prepare("
                INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, total_price) 
                VALUES (:order_id, :product_id, :product_name, :product_price, :quantity, :total_price)
            ");
            $order_item_stmt->bindParam(':order_id', $order_id);
            $order_item_stmt->bindParam(':product_id', $item['id']);
            $order_item_stmt->bindParam(':product_name', $item['name']);
            $order_item_stmt->bindParam(':product_price', $item['price']);
            $order_item_stmt->bindParam(':quantity', $item['quantity']);
            $total_price = $item['price'] * $item['quantity'];
            $order_item_stmt->bindParam(':total_price', $total_price);
            $order_item_stmt->execute();
        }
        
        // Clear cart
        clearCart($user_id);
        
        $pdo->commit();
        return $order_id;
    } catch (Exception $e) {
        $pdo->rollback();
        throw $e;
    }
}

// Function to get orders for user
function getUserOrders($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get order details
function getOrderDetails($order_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT o.*, oi.*, p.image_url 
        FROM orders o 
        JOIN order_items oi ON o.id = oi.order_id 
        JOIN products p ON oi.product_id = p.id 
        WHERE o.id = :order_id
    ");
    $stmt->bindParam(':order_id', $order_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to search products
function searchProducts($query) {
    global $pdo;
    $sql = "SELECT * FROM products WHERE is_active = 1 AND (name LIKE :query OR description LIKE :query)";
    $stmt = $pdo->prepare($sql);
    $search_query = "%$query%";
    $stmt->bindParam(':query', $search_query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get best seller products (this is a simplified version)
function getBestSellerProducts($limit = 4) {
    global $pdo;
    // In a real implementation, this would be based on actual sales data
    // For now, we'll just return the most recently added active products
    $sql = "SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC LIMIT :limit";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get proper image path (local only)
function getImageUrl($image_path) {
    if (!$image_path) {
        return 'https://via.placeholder.com/300x300/e2e8f0/64748b?text=No+Image';
    }

    // For local paths, return the full path from the site root
    return $image_path;
}
?>
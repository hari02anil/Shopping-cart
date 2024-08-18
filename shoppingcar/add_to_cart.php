<?php
session_start();
include 'db.php'; // Adjust path if necessary

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];

    // Validate input
    if (!is_numeric($product_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid product ID']);
        exit();
    }

    try {
        // Fetch product details to check stock availability
        $stmt = $pdo->prepare("
            SELECT stock FROM products
            WHERE id = :product_id
        ");
        $stmt->execute(['product_id' => $product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            echo json_encode( 'Product not found');
            exit();
        }

        if ($product['stock'] <= 0) {
            echo json_encode( 'Product is out of stock');
            exit();
        }
        if ($product['stock'] > 0){
        // Check if the product already exists in the cart
        $stmt = $pdo->prepare("
            SELECT id, quantity FROM cart
            WHERE user_id = :user_id AND product_id = :product_id
        ");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
        $existing_cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_cart_item) {
            // Check if adding one more item exceeds the available stock
            if ($existing_cart_item['quantity'] + 1 > $product['stock']) {
                echo json_encode('Not enough stock available');
                exit();
            }

            // If the product is already in the cart, increment the quantity
            $stmt = $pdo->prepare("
                UPDATE cart
                SET quantity = quantity + 1
                WHERE id = :cart_id
            ");
            $stmt->execute(['cart_id' => $existing_cart_item['id']]);
            echo json_encode('Product quantity increased in the cart');
        } else {
            // If the product is not in the cart, add it
            $stmt = $pdo->prepare("
                INSERT INTO cart (user_id, product_id, quantity)
                VALUES (:user_id, :product_id, 1)
            ");
            $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
            echo json_encode( "Product added to cart");
        }
    }

        
        
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

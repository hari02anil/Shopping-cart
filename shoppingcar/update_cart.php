<?php
session_start();
include 'db.php'; // Adjust path if necessary

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];

    // Validate inputs
    if (!is_numeric($cart_id) || !is_numeric($quantity) || $quantity < 1) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        exit();
    }

    try {
        // Check if the cart item belongs to the logged-in user
        $stmt = $pdo->prepare("
            SELECT * FROM cart
            WHERE id = :cart_id AND user_id = :user_id
        ");
        $stmt->execute(['cart_id' => $cart_id, 'user_id' => $user_id]);
        $cart_item = $stmt->fetch();

        if (!$cart_item) {
            echo json_encode(['status' => 'error', 'message' => 'Cart item not found']);
            exit();
        }

        // Update the quantity of the cart item
        $stmt = $pdo->prepare("
            UPDATE cart
            SET quantity = :quantity
            WHERE id = :cart_id
        ");
        $stmt->execute(['quantity' => $quantity, 'cart_id' => $cart_id]);

        echo json_encode(['status' => 'success', 'message' => 'Cart updated successfully']);
        
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

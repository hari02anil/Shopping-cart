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

    // Validate input
    if (!is_numeric($cart_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid cart ID']);
        exit();
    }

    try {
        // Delete the item from the cart
        $stmt = $pdo->prepare("
            DELETE FROM cart
            WHERE id = :cart_id AND user_id = :user_id
        ");
        $stmt->execute(['cart_id' => $cart_id, 'user_id' => $user_id]);

        echo json_encode(['status' => 'success', 'message' => 'Item removed from cart']);
        
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

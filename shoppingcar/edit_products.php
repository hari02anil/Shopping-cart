<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $productId = intval($_POST['id']);
    
    // Prepare the DELETE statement
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    
    // Execute the DELETE statement
    if ($stmt->execute([$productId])) {
        echo "Product deleted successfully!";
        header("Location: edit_products.php"); // Redirect to refresh the page
        exit();
    } else {
        echo "Error deleting product: " . $stmt->errorInfo()[2];
    }
}

// Fetch all products
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Products</title>
    <link rel="stylesheet" href="styleeproducts.css"> <!-- Link to the CSS file -->
</head>
<body>
    <h1>Edit Products</h1>

    <ul>
        <?php foreach ($products as $product): ?>
            <li>
                <?= htmlspecialchars($product['name']) ?>
                <a href="edit_product.php?id=<?= $product['id'] ?>">
                    <button>Edit</button>
                </a>
                <!-- Delete Form -->
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                    <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you want to delete this product?');">
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="dashboard.php"><button>Back to Dashboard</button></a>
</body>
</html>



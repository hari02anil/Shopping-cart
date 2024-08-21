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
        echo "<script>
            alert('Product deleted successfully!');
            window.location.href = 'edit_products.php';
        </script>";
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
    
    <style>
        body {
            background-image: url('shop img.jpg');
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        h1 {
            margin-top: 20px;
            color: #333;
            text-align: center; /* Center the title */
        }
        ul {
            list-style-type: none;
            padding: 0;
            margin: 20px auto; /* Center the list */
            width: 90%;
            max-width: 800px;
        }
        li {
            background-color: #fff;
            margin-bottom: 10px;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        button:hover {
            background-color: #218838;
        }
        .delete-button {
            
            background-color: #dc3545;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 10px 15px;
    font-size: 0.9em;
    cursor: pointer;
    transition: background-color 0.3s;
        }
        .delete-button:hover {
            background-color: #c82333;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        a button {
            text-decoration: none;
        }
        .back-button {
            background-color: #556d7a;
            margin: 20px 0;
        }
        .back-button:hover {
            background-color: #5a6268;
        }
        .back-button-container {
            padding: 0 20px; 
        }
        .back-button-container {
            display: flex;
            justify-content: flex-start; 
        }
    </style>
</head>
<body>
    <h1>Edit Products</h1>

    <ul>
        <?php foreach ($products as $product): ?>
            <li>
                <?= htmlspecialchars($product['name']) ?>
                <div class="actions">
                    <a href="edit_product.php?id=<?= $product['id'] ?>">
                        <button>Edit</button>
                    </a>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        <input type="submit" name="delete" value="Delete" class="delete-button" onclick="return confirm('Are you sure you want to delete this product?');">
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="back-button-container">
    <a href="dashboard.php"><button class="back-button">Back to Dashboard</button></a>
        </div>
</body>
</html>

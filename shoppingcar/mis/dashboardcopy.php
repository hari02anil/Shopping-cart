<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .profile-pic {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            margin: 10px 0;
        }
        a {
            text-decoration: none;
        }
        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?= htmlspecialchars($user['name']) ?>!</h1>

        <?php if (!empty($user['profile_photo'])): ?>
            <img src="<?= htmlspecialchars($user['profile_photo']) ?> alt="Profile Picture" class="profile-pic">
        <?php endif; ?>

        <?php if ($user['role'] === 'admin'): ?>
            <h2>Admin Options</h2>
            <ul>
                <li><a href="add_product.php"><button>Add Product</button></a></li>
                <li><a href="edit_products.php"><button>Edit Products</button></a></li>
                <li><a href="view_report.php"><button>View Orders Report</button></a></li>
            </ul>
        <?php else: ?>
            <h2>User Options</h2>
            <ul>
                <li><a href="list_products.php"><button>Browse Products</button></a></li>
                <li><a href="checkout.php"><button>View Cart / Checkout</button></a></li>
            </ul>
        <?php endif; ?>

        <br><br>
        <a href="logout.php"><button>Logout</button></a>
    </div>
</body>
</html>
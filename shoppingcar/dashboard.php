<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$n = $_SESSION['user_id'];
// Fetch user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$n]);
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
            background-image: url('shop img.jpg');
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .profile-section {
            display: flex;
            flex-direction: column;
            align-items: flex-start; /* Align items to the right */
            margin-bottom: 20px;
        }
        .profile-pic {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 10px; /* Space between the picture and details */
        }
        .profile-details {
            text-align: left; /* Align text to the right */
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

        <div class="profile-section">
            <?php if (!empty($user['profile_photo'])): ?>
                <img src="<?= htmlspecialchars($user['profile_photo']) ?>" alt="Profile Picture" class="profile-pic">
            <?php endif; ?>

            <div class="profile-details">
                <?php if (!empty($user['email'])): ?>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <?php endif; ?>
                <?php if (!empty($user['phone'])): ?>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
                <?php endif; ?>
                <?php if (!empty($user['dob'])): ?>
                    <p><strong>Date of Birth:</strong> <?= htmlspecialchars($user['dob']) ?></p>
                <?php endif; ?>
                <?php if (!empty($user['gender'])): ?>
                    <p><strong>Gender:</strong> <?= htmlspecialchars($user['gender']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($user['role'] === 'admin'): ?>
            <center><h2>Admin Options</h2></center>
            <ul><center>
                <li><a href="add_product.php"><button>Add Product</button></a></li>
                <li><a href="edit_products.php"><button>Edit Products</button></a></li>
                <li><a href="download_report.php"><button>Download Report</button></a></li>
            </ul></center>
        <?php else: ?>
            <h2>User Options</h2>
            <ul>
                <li><a href="products.php"><button>Browse Products</button></a></li>
                <li><a href="cart.php"><button>View Cart / Checkout</button></a></li>
                <li><a href="orders.php"><button>View my orders</button></a></li>
            </ul>
        <?php endif; ?>

        <br><br>
        <a href="logout.php"><button>Logout</button></a>
    </div>
    
</body>
</html>



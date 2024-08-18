<!DOCTYPE html>
<html lang="en">
<head>
    
    <link rel="stylesheet" href="style.css"> <!-- Link to the CSS file -->
</head>
<body>
    
</body>
</html>

<?php
include 'db.php';
session_start();

if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = '';

    if (isset($_FILES['image'])) {
        $image = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $description, $price, $stock, $image]);

    echo "Product added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to the CSS file -->
</head>
<body>
    <h1>Add New Product</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input type="number" name="price" placeholder="Price" step="0.01" required>
        <input type="number" name="stock" placeholder="Stock" required>
        <input type="file" name="image">
        <input type="submit" value="Add Product">
    </form>
    <a href="dashboard.php"><button>Back to Dashboard</button></a>
</body>
</html>
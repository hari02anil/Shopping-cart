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

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Product Name" required><br>
    <textarea name="description" placeholder="Description"></textarea><br>
    <input type="number" name="price" placeholder="Price" step="0.01" required><br>
    <input type="number" name="stock" placeholder="Stock" required><br>
    <input type="file" name="image"><br>
    <input type="submit" value="Add Product">
</form>

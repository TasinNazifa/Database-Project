<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $supplier = $_POST['supplier'];
    
    $sql = "INSERT INTO Inventory (ItemName, Description, CategoryName, Quantity, UnitPrice, SupplierName) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssids", $name, $description, $category, $quantity, $price, $supplier);
    $stmt->execute();
    echo "Medicine added successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Medicine</title>
</head>
<body>
    <h2>Add Medicine</h2>
    <form method="POST">
        <label>Name:</label> <input type="text" name="name" required><br>
        <label>Description:</label> <input type="text" name="description" required><br>
        <label>Category:</label> <input type="text" name="category" required><br>
        <label>Quantity:</label> <input type="number" name="quantity" required><br>
        <label>Price:</label> <input type="text" name="price" required><br>
        <label>Supplier:</label> <input type="text" name="supplier" required><br>
        <button type="submit">Add</button>
    </form>
</body>
</html>
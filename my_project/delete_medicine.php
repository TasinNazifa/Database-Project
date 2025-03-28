<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['item_id'];

    $sql = "DELETE FROM Inventory WHERE ItemID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Medicine deleted successfully!";
    } else {
        echo "Error deleting medicine: " . $conn->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Medicine</title>
</head>
<body>
    <h2>Delete Medicine</h2>
    <form method="POST">
        <label>Item ID:</label> <input type="number" name="item_id" required><br>
        <button type="submit">Delete</button>
    </form>
</body>
</html>

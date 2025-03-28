<?php
session_start();
include("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $email = $_POST['email'];

    $sql = "SELECT * FROM Customers WHERE customer_id = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $customer_id, $email); // "i" for int, "s" for string
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['customer_logged_in'] = true;
        $_SESSION['customer_id'] = $user['customer_id'];
        $_SESSION['customer_name'] = $user['name'];

        header("Location: customer_dashboard.php");
        exit();
    } else {
        $error = "Invalid Customer ID or Email!";
    }
}
?>

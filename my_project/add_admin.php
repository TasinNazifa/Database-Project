<?php
include("db_connection.php");

$username = "Admin1";
$password = password_hash("abcd", PASSWORD_BCRYPT);
$username = "Admin2";
$password = password_hash("wxyz", PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO Admins (Username, PasswordHash) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$stmt->close();
$conn->close();

echo "Admin added successfully!";
?>

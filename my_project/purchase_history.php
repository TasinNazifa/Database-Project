<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php"); // Redirect to admin login page if not logged in
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all purchase history
$query = "SELECT o.OrderID, o.CustomerID, o.OrderDate, oi.ItemID, i.ItemName, oi.Quantity, oi.UnitPrice, oi.TotalPrice 
          FROM orders o
          JOIN order_items oi ON o.OrderID = oi.OrderID
          JOIN inventory i ON oi.ItemID = i.ItemID
          ORDER BY o.OrderDate";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid black;
            text-align: center;
        }

        th {
            background: #6a0dad;
            color: white;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        .back-btn {
            background: #6a0dad;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }

        .back-btn:hover {
            background: #4b0082;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Purchase History</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Customer ID</th>
                <th>Order Date</th>
                <th>Item ID</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['OrderID']); ?></td>
                    <td><?php echo htmlspecialchars($row['CustomerID']); ?></td>
                    <td><?php echo htmlspecialchars($row['OrderDate']); ?></td>
                    <td><?php echo htmlspecialchars($row['ItemID']); ?></td>
                    <td><?php echo htmlspecialchars($row['ItemName']); ?></td>
                    <td><?php echo htmlspecialchars($row['Quantity']); ?></td>
                    <td><?php echo htmlspecialchars($row['UnitPrice']); ?></td>
                    <td><?php echo htmlspecialchars($row['TotalPrice']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No transactions found.</p>
    <?php endif; ?>

    <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>

</body>
</html>

<?php $conn->close(); ?>

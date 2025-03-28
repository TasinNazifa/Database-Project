<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
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

// Fetch total sales amount and quantity
$total_sales_query = "SELECT SUM(TotalPrice) AS total_revenue, SUM(Quantity) AS total_quantity FROM order_items";
$total_sales_result = $conn->query($total_sales_query);
$total_data = $total_sales_result->fetch_assoc();
$total_revenue = $total_data['total_revenue'] ?? 0;
$total_quantity = $total_data['total_quantity'] ?? 0;

// Fetch individual medicine sales
$medicine_sales_query = "
    SELECT i.ItemName, SUM(oi.Quantity) AS total_sold, SUM(oi.TotalPrice) AS revenue 
    FROM order_items oi
    JOIN inventory i ON oi.ItemID = i.ItemID
    GROUP BY i.ItemName
    ORDER BY revenue DESC";
$medicine_sales_result = $conn->query($medicine_sales_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Sales</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
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
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #6a0dad;
            color: white;
        }

        .summary {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        .back-btn {
            background: red;
            padding: 10px 15px;
            text-decoration: none;
            color: white;
            display: inline-block;
            margin-top: 20px;
            border-radius: 5px;
        }

        .back-btn:hover {
            background: darkred;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Total Sales Report</h2>

    <p class="summary">Total Revenue: <strong>$<?php echo number_format($total_revenue, 2); ?></strong></p>
    <p class="summary">Total Quantity Sold: <strong><?php echo $total_quantity; ?></strong> units</p>

    <table>
        <tr>
            <th>Medicine Name</th>
            <th>Total Quantity Sold</th>
            <th>Total Revenue</th>
        </tr>
        <?php while ($row = $medicine_sales_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['ItemName']; ?></td>
                <td><?php echo $row['total_sold']; ?></td>
                <td>$<?php echo number_format($row['revenue'], 2); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>

</body>
</html>

<?php $conn->close(); ?>

<?php
// Ensure the page is accessed through a valid form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = htmlspecialchars($_POST['customer_id']);
    $email = htmlspecialchars($_POST['email']);
} else {
    // Redirect or deny access if accessed directly
    header("Location: login.php");
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

// Fetch order history with item details for the customer
$sql = "SELECT o.OrderID, oi.ItemID, oi.Quantity, oi.UnitPrice, oi.TotalPrice 
        FROM orders o 
        INNER JOIN order_items oi ON o.OrderID = oi.OrderID
        WHERE o.CustomerID = '$customer_id'
        ORDER BY o.OrderID DESC"; // Orders by OrderID in descending order

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .navbar { background-color: purple; padding: 1rem; text-align: center; color: white; }
        .content { padding: 2rem; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 10px; text-align: center; }
        .return-button { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Order History</h1>
    </div>

    <div class="content">
        <h2>Your Previous Purchases</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Item ID</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['OrderID']; ?></td>
                        <td><?php echo $row['ItemID']; ?></td>
                        <td><?php echo $row['Quantity']; ?></td>
                        <td><?php echo $row['UnitPrice']; ?></td>
                        <td><?php echo $row['TotalPrice']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No order history found for this customer.</p>
        <?php endif; ?>

        <!-- Button to return to the customer dashboard -->
        <div class="return-button">
            <form action="customer_dashboard.php" method="POST">
                <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
                <input type="hidden" name="email" value="<?php echo $email; ?>">
                <button type="submit">Return to Dashboard</button>
            </form>
        </div>
    </div>

</body>
</html>

<?php
$conn->close();
?>

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

$sql = "SELECT * FROM inventory";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: purple;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            color: white;
            margin: 0;
        }
        .nav-buttons {
            display: flex;
            gap: 1rem;
        }
        .nav-buttons form {
            margin: 0;
        }
        .nav-buttons button {
            background-color: transparent;
            border: 2px solid white;
            color: white;
            padding: 0.5rem 1rem;
            cursor: pointer;
            font-size: 1rem;
            border-radius: 5px;
        }
        .nav-buttons button:hover {
            background-color: white;
            color: purple;
        }
        .content {
            padding: 2rem;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <h1>Customer Dashboard</h1>
        <div class="nav-buttons">
            <form action="cart.php" method="POST">
                <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
                <input type="hidden" name="email" value="<?php echo $email; ?>">
                <button type="submit">Cart</button>
            </form>
            <form action="history.php" method="POST">
                <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
                <input type="hidden" name="email" value="<?php echo $email; ?>">
                <button type="submit">History</button>
            </form>
        </div>
    </div>

    <!-- Medicine Inventory Table -->
    <div class="content">
        <h2>Select Medicines to Purchase</h2>
        <form action="cart.php" method="POST">
            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
            <input type="hidden" name="email" value="<?php echo $email; ?>">
            <table>
                <tr>
                    <th>Select</th>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Supplier Name</th>
                    <th>Category Name</th>
                    <th>Date Added</th>
                    <th>Last Updated</th>
                    <th>Order Quantity</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><input type="checkbox" name="item_id[]" value="<?php echo $row['ItemID']; ?>"></td>
                        <td><?php echo $row['ItemID']; ?></td>
                        <td><?php echo $row['ItemName']; ?></td>
                        <td><?php echo $row['Description']; ?></td>
                        <td><?php echo $row['Quantity']; ?></td>
                        <td><?php echo $row['UnitPrice']; ?></td>
                        <td><?php echo $row['SupplierName']; ?></td>
                        <td><?php echo $row['CategoryName']; ?></td>
                        <td><?php echo $row['DateAdded']; ?></td>
                        <td><?php echo $row['LastUpdated']; ?></td>
                        <td><input type="number" name="quantity[<?php echo $row['ItemID']; ?>]" min="1" max="<?php echo $row['Quantity']; ?>" value="1"></td>
                    </tr>
                <?php endwhile; ?>
            </table>
            <button type="submit">Add to Cart</button>
        </form>
    </div>

</body>
</html>

<?php
$conn->close();
?>

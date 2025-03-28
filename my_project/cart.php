<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testdb";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = htmlspecialchars($_POST['customer_id']);
    $email = htmlspecialchars($_POST['email']);

    if (!isset($_POST['item_id']) || empty($_POST['item_id'])) {
        echo "No items selected!";
        exit();
    }

    $selected_items = $_POST['item_id']; // Array of selected Item IDs
    $quantities = $_POST['quantity']; // Associative array (ItemID => quantity)

    // Create an order entry
    $insert_order = "INSERT INTO orders (CustomerID) VALUES ('$customer_id')";
    if ($conn->query($insert_order) === TRUE) {
        $order_id = $conn->insert_id; // Get the last inserted Order ID
        $_SESSION['cart'] = []; // Initialize cart session

        foreach ($selected_items as $item_id) {
            $purchase_quantity = intval($quantities[$item_id] ?? 0);

            // Fetch current stock & price from inventory
            $query = "SELECT ItemName, Quantity, UnitPrice FROM inventory WHERE ItemID = '$item_id'";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $stock_quantity = $row['Quantity'];
                $unit_price = $row['UnitPrice'];
                $item_name = $row['ItemName'];
                $total_price = $purchase_quantity * $unit_price;

                if ($purchase_quantity > 0 && $purchase_quantity <= $stock_quantity) {
                    // Insert into order_items table
                    $insert_order_item = "INSERT INTO order_items (OrderID, ItemID, Quantity, UnitPrice, TotalPrice) 
                                          VALUES ('$order_id', '$item_id', '$purchase_quantity', '$unit_price', '$total_price')";
                    $conn->query($insert_order_item);

                    // Deduct purchased quantity from inventory
                    $new_quantity = $stock_quantity - $purchase_quantity;
                    $update_inventory = "UPDATE inventory SET Quantity = '$new_quantity' WHERE ItemID = '$item_id'";
                    $conn->query($update_inventory);

                    // Store item details in session cart
                    $_SESSION['cart'][] = [
                        'ItemID' => $item_id,
                        'ItemName' => $item_name,
                        'Quantity' => $purchase_quantity,
                        'UnitPrice' => $unit_price,
                        'TotalPrice' => $total_price
                    ];
                } else {
                    echo "Error: Not enough stock for Item ID: $item_id";
                    exit();
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .navbar { background-color: purple; padding: 1rem; color: white; text-align: center; }
        .content { padding: 2rem; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid black; text-align: center; }
        button { padding: 10px 15px; background-color: purple; color: white; border: none; cursor: pointer; }
        button:hover { background-color: darkpurple; }
    </style>
</head>
<body>

<div class="navbar">
    <h1>Cart</h1>
</div>

<div class="content">
    <h2>Selected Medicines</h2>
    <form action="checkout.php" method="POST">
        <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($customer_id); ?>">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
        
        <table>
            <tr>
                <th>Item ID</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
            </tr>
            <?php 
            $total_price = 0;
            if (!empty($_SESSION['cart'])):
                foreach ($_SESSION['cart'] as $item): 
                    $total_price += $item['TotalPrice'];
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['ItemID']); ?></td>
                    <td><?php echo htmlspecialchars($item['ItemName']); ?></td>
                    <td><?php echo htmlspecialchars($item['Quantity']); ?></td>
                    <td><?php echo htmlspecialchars($item['UnitPrice']); ?></td>
                    <td><?php echo htmlspecialchars($item['TotalPrice']); ?></td>
                </tr>
            <?php endforeach; ?>
                <tr>
                    <td colspan="4"><strong>Total Price</strong></td>
                    <td><strong><?php echo htmlspecialchars($total_price); ?></strong></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="5">No items in the cart</td>
                </tr>
            <?php endif; ?>
        </table>

        <h3>Select Payment Method</h3>
        <select name="payment_method" required>
            <option value="credit_card">Credit Card</option>
            <option value="paypal">PayPal</option>
            <option value="cod">Cash on Delivery</option>
        </select>
        <br><br>
        <button type="submit">Proceed to Checkout</button>
    </form>
</div>

</body>
</html>

<?php $conn->close(); ?>

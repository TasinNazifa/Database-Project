<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = htmlspecialchars($_POST['customer_id']);
    $email = htmlspecialchars($_POST['email']);
    $payment_method = htmlspecialchars($_POST['payment_method']);

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "testdb";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Calculate total price
    $total_price = 0;
    foreach ($_SESSION['cart'] as $item) {
        $item_id = $item['ItemID'];
        $quantity = $item['Quantity'];

        $result = $conn->query("SELECT UnitPrice FROM inventory WHERE ItemID = '$item_id'");
        if ($row = $result->fetch_assoc()) {
            $unit_price = $row['UnitPrice'];
            $total_price += $unit_price * $quantity;
        }
    }

    // Insert order into the `orders` table
    $sql = "INSERT INTO orders (CustomerID, Email, TotalPrice, PaymentMethod) VALUES ('$customer_id', '$email', '$total_price', '$payment_method')";
    
    if ($conn->query($sql) === TRUE) {
        $order_id = $conn->insert_id; // Get the last inserted order ID

        // Insert each item into `order_items`
        foreach ($_SESSION['cart'] as $item) {
            $item_id = $item['ItemID'];
            $quantity = $item['Quantity'];

            $result = $conn->query("SELECT UnitPrice FROM inventory WHERE ItemID = '$item_id'");
            if ($row = $result->fetch_assoc()) {
                $unit_price = $row['UnitPrice'];
                $total = $unit_price * $quantity;

                $conn->query("INSERT INTO order_items (OrderID, ItemID, Quantity, UnitPrice, TotalPrice) 
                              VALUES ('$order_id', '$item_id', '$quantity', '$unit_price', '$total')");
            }
        }

        // Clear cart session
        unset($_SESSION['cart']);

        // Redirect to order confirmation page
        header("Location: order_confirmation.php?order_id=$order_id");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
} else {
    header("Location: cart.php");
    exit();
}
?>

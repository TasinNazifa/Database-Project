<?php
if (!isset($_GET['order_id'])) {
    header("Location: customer_dashboard.php");
    exit();
}
$order_id = $_GET['order_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Order Placed Successfully!</h1>
    <p>Your Order ID is: <strong><?php echo $order_id; ?></strong></p>
    
    <!-- Button to return to customer dashboard -->
    <form action="customer_dashboard.php" method="POST">
        <input type="hidden" name="customer_id" value="<?php echo $_SESSION['customer_id']; ?>">
        <input type="hidden" name="email" value="<?php echo $_SESSION['email']; ?>">
        <button type="submit">Return to Dashboard</button>
    </form>
</body>
</html>

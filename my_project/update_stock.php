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

// Handle stock update request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST['item_id'];
    $change_quantity = intval($_POST['change_quantity']);

    // Fetch current stock
    $query = "SELECT Quantity FROM inventory WHERE ItemID = '$item_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_quantity = $row['Quantity'];
        $new_quantity = $current_quantity + $change_quantity;

        // Ensure stock doesn't go below zero
        if ($new_quantity < 0) {
            $message = "Error: Stock cannot be negative.";
        } else {
            // Update stock in database
            $update_query = "UPDATE inventory SET Quantity = '$new_quantity' WHERE ItemID = '$item_id'";
            if ($conn->query($update_query) === TRUE) {
                $message = "Stock updated successfully!";
            } else {
                $message = "Error updating stock: " . $conn->error;
            }
        }
    } else {
        $message = "Invalid Medicine ID.";
    }
}

// Fetch all medicines
$medicines_query = "SELECT ItemID, ItemName, Quantity FROM inventory ORDER BY ItemName ASC";
$medicines_result = $conn->query($medicines_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Stock</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
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

        form {
            margin-top: 20px;
        }

        select, input, button {
            padding: 10px;
            margin: 10px 0;
            width: 80%;
            font-size: 16px;
        }

        button {
            background: #6a0dad;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background: #4b0082;
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

        .message {
            margin-top: 10px;
            font-weight: bold;
            color: green;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Update Medicine Stock</h2>

    <?php if (isset($message)): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="item_id">Select Medicine:</label>
        <select name="item_id" required>
            <?php while ($medicine = $medicines_result->fetch_assoc()): ?>
                <option value="<?php echo $medicine['ItemID']; ?>">
                    <?php echo $medicine['ItemName'] . " (Stock: " . $medicine['Quantity'] . ")"; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="change_quantity">Enter Quantity (+/-):</label>
        <input type="number" name="change_quantity" required>

        <button type="submit">Update Stock</button>
    </form>

    <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>

</body>
</html>

<?php $conn->close(); ?>

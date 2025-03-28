<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
            background: url('/my_project/images/adminpic.jpg') no-repeat center center fixed;
            background-size: 50% auto; /* Reduce size to 50% */
            background-position: 60% center;
        }

        /* Sidebar styles */
        .sidebar {
            width: 250px;
            background: rgba(106, 13, 173, 0.9); /* Semi-transparent */
            color: white;
            padding: 20px;
            position: fixed;
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px;
            display: block;
            background: rgba(75, 0, 130, 0.8);
            border-radius: 5px;
            transition: 0.3s;
            text-align: center;
        }

        .sidebar a:hover {
            background: rgba(58, 0, 102, 0.8);
        }

        /* Main content area */
        .main-content {
            margin-left: 270px;
            padding: 20px;
            width: 100%;
        }
        .background-image {
            background: url('/my_project/images/adminpic.jpg') no-repeat center center;
            background-size: contain; /* Smaller image */
            width: 200px; /* Adjust size */
            height: 200px; /* Adjust size */
        }

        .container {
            background: rgba(255, 255, 255, 0.7); /* Light transparency */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            margin-bottom: 15px;
        }

        .logout-btn {
            background-color: red;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }

        .logout-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <h3>Admin Menu</h3>
        <a href="purchase_history.php">Purchase History</a>
        <a href="add_medicine.php">Add Medicine</a>
        <a href="total_sales.php">Total Sales</a>
        <a href="delete_medicine.php">Delete Medicine</a>
        <a href="fetchcustomer.php">Customer</a>
        <a href="update_stock.php">Update Stock</a>
        <a href="admin_logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h2>Welcome to Admin Dashboard</h2>
            <p>You are now logged in as Admin.</p>
        </div>
    </div>

</body>
</html>

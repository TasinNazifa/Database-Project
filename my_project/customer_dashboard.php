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
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .background-image {
            background: url('/my_project/images/customerimage.jpg') no-repeat center center;
            background-size: contain; /* Smaller image */
            width: 500px; /* Adjust size */
            height: 500px; /* Adjust size */
        }
        .welcome-text {
            color: black;
            font-size: 2rem;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <h1>Customer Dashboard</h1>
        <div class="nav-buttons">
            <form action="purchase.php" method="POST">
                <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
                <input type="hidden" name="email" value="<?php echo $email; ?>">
                <button type="submit">Purchase</button>
            </form>

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
            <form action="logout.php" method="POST">
                <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
                <input type="hidden" name="email" value="<?php echo $email; ?>">
                <button type="submit">Log Out</button>
            </form>
        </div>
    </div>

    <!-- Background Image and Welcome Text -->
    <div class="content">
        <div class="background-image"></div>
        <div class="welcome-text">Welcome to Your Dashboard</div>
    </div>

</body>
</html>

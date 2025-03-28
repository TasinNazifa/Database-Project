<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Inventory System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        body {
            font-family: Arial, sans-serif;
            text-align: center;
            overflow-x: hidden;
            position: relative;
        }

        /* Background image with overlay */
        .background-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('images/medicine.jpg') no-repeat center center fixed;
            background-size: cover;
            z-index: -1;
        }
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #6a0dad;
            color: white;
            padding: 20px 0;
            font-size: 24px;
            text-align: center;
            z-index: 1000;
        }

        .menu {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
        }

        .menu a {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #6a0dad;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }

        .menu a:hover {
            background-color: #4b0082;
        }

        .section {
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 20px;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(5px); /* Slight blur effect */
            border-radius: 10px;
            z-index: 1;
        }

        .section h2 {
            margin-bottom: 20px;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        #inventory, #customer-form {
            display: none;
        }
    </style>
    <script>
        function showSection(sectionId) {
            document.querySelectorAll(".section").forEach(section => {
                section.style.display = "none";
            });
            document.getElementById(sectionId).style.display = "flex";
            document.getElementById(sectionId).scrollIntoView({ behavior: "smooth" });
        }

        function fetchCustomer() {
            var customerId = document.getElementById("customer-id").value;
            if (customerId) {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "fetchcustomer.php?customer_id=" + customerId, true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        document.getElementById("customer-data").innerHTML = xhr.responseText;
                        document.getElementById("customer-data").style.display = "block";
                    }
                };
                xhr.send();
            }
        }

        function redirectToAdmin() {
            window.location.href = "admin_login.php";
        }
    </script>
</head>
<body>
    
    <!-- Background Image with Overlay -->
    <div class="background-container">
        <div class="background-overlay"></div>
    </div>

    <div class="header">
        <div>Medicine Inventory System</div>
        <div class="menu">
            <a href="#" onclick="showSection('home')">Home</a>
            <a href="#" onclick="showSection('inventory')">Inventory</a>
            <a href="login.php">Login</a>
            <a href="signup.php">Sign Up</a>
            <a href="admin_login.php" onclick="redirectToAdmin()">Admin</a> 
        </div>
    </div>

    <div id="home" class="section">
        <h2>Welcome to the Medicine Inventory System</h2>
        <p>Your trusted partner in managing medicine stocks efficiently.</p>
    </div>

    <div id="inventory" class="section">
        <h2>Inventory Table</h2>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "testdb";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM Inventory";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table><tr><th>Item ID</th><th>Item Name</th><th>Description</th><th>Quantity</th><th>Unit Price</th><th>Supplier</th><th>Category</th><th>Date Added</th><th>Last Updated</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row["ItemID"]) . "</td>
                          <td>" . htmlspecialchars($row["ItemName"]) . "</td>
                          <td>" . htmlspecialchars($row["Description"]) . "</td>
                          <td>" . htmlspecialchars($row["Quantity"]) . "</td>
                          <td>" . htmlspecialchars($row["UnitPrice"]) . "</td>
                          <td>" . htmlspecialchars($row["SupplierName"]) . "</td>
                          <td>" . htmlspecialchars($row["CategoryName"]) . "</td>
                          <td>" . htmlspecialchars($row["DateAdded"]) . "</td>
                          <td>" . htmlspecialchars($row["LastUpdated"]) . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "No inventory data available.";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>

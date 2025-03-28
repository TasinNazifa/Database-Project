<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$customerData = "";
if (isset($_GET['customer_id'])) {
    $customer_id = intval($_GET['customer_id']);
    
    // Prepare and execute query
    $sql = "SELECT * FROM Customers WHERE Customer_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $customerData .= "<tr>";
            $customerData .= "<td>" . htmlspecialchars($row["Customer_ID"]) . "</td>";
            $customerData .= "<td>" . htmlspecialchars($row["Customer_Name"]) . "</td>";
            $customerData .= "<td>" . htmlspecialchars($row["Contact_Info"]) . "</td>";
            $customerData .= "<td>" . htmlspecialchars($row["Loyalty"]) . "</td>";
            $customerData .= "<td>" . htmlspecialchars($row["History"]) . "</td>";
            $customerData .= "</tr>";
        }
    } else {
        $customerData = "<tr><td colspan='5'>No customer found</td></tr>";
    }
    
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Inventory System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        form {
            margin: 20px 0;
        }
        input[type="number"] {
            padding: 10px;
            width: 80%;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Medicine Inventory System</h1>
        <form action="" method="GET">
            <input type="number" name="customer_id" placeholder="Enter Customer ID" required>
            <button type="submit">Fetch</button>
        </form>

        <?php if (!empty($customerData)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Customer ID</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Loyalty</th>
                        <th>History</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $customerData; ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</body>
</html>

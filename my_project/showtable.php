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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers Table</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
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
    <h1>Customers Table</h1>
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
            <?php
            // Fetch data from the Customers table
            $sql = "SELECT * FROM Customers";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row into the table
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["Customer_ID"] . "</td>";
                    echo "<td>" . $row["Customer_Name"] . "</td>";
                    echo "<td>" . $row["Contact_Info"] . "</td>";
                    echo "<td>" . $row["Loyalty"] . "</td>";
                    echo "<td>" . $row["History"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No records found</td></tr>";
            }

            // Close the connection
            $conn->close();
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
$servername = "localhost";
$username = "root"; // Your database username
$password = "123"; // Your database password
$dbname = "dj_booking";
$port = 3377; // Specify the port number

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>

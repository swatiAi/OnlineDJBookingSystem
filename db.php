<?php
$servername = "localhost";
$username = "root";
$password = "123";
$dbname = "dj_booking";
$port = 3377; // Specify the port number

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
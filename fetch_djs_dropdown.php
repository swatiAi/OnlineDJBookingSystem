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

$sql = "SELECT dj_id, name FROM djs";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<option value='" . htmlspecialchars($row["dj_id"]) . "'>" . htmlspecialchars($row["name"]) . "</option>";
    }
} else {
    echo "<option value=''>No DJs available</option>";
}
$conn->close();
?>
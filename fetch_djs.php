
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

$sql = "SELECT name, genre, availability FROM djs";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . htmlspecialchars($row["name"]). "</td><td>" . htmlspecialchars($row["genre"]). "</td><td>" . htmlspecialchars($row["availability"]). "</td></tr>";
    }
} else {
    echo "<tr><td colspan='3'>No DJs available</td></tr>";
}
$conn->close();
?>

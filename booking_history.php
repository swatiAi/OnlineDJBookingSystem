<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch bookings for the logged-in user
$stmt = $conn->prepare("SELECT b.booking_id, d.name AS dj_name, b.event_date, b.status 
                        FROM bookings b 
                        JOIN djs d ON b.dj_id = d.dj_id 
                        WHERE b.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Your Booking History</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="booking_history.php">Booking History</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <section class="booking-history">
                <h2>Your Bookings</h2>
                <table>
                    <thead>
                        <tr>
                            <th>DJ</th>
                            <th>Event Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($row['dj_name']) . "</td>
                                        <td>" . htmlspecialchars($row['event_date']) . "</td>
                                        <td>" . htmlspecialchars($row['status']) . "</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No bookings found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT event_date, status FROM bookings WHERE booking_id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->bind_result($event_date, $status);
    $stmt->fetch();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'];
    $event_date = $_POST['event_date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE bookings SET event_date = ?, status = ? WHERE booking_id = ?");
    $stmt->bind_param("ssi", $event_date, $status, $booking_id);

    if ($stmt->execute()) {
        header("Location: admin.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Edit Booking</h2>
        <form action="edit_booking.php" method="post">
            <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking_id); ?>">
            <label for="event_date">Event Date:</label>
            <input type="date" id="event_date" name="event_date" value="<?php echo htmlspecialchars($event_date); ?>" required>
            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="Pending" <?php if ($status == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="Confirmed" <?php if ($status == 'Confirmed') echo 'selected'; ?>>Confirmed</option>
                <option value="Cancelled" <?php if ($status == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
            </select>
            <button type="submit">Update Booking</button>
        </form>
        <p><a href="admin.php">Back to Admin Dashboard</a></p>
    </div>
</body>
</html>

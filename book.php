<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // This includes the Composer autoload file
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$response_message = "";

$user_id = $_SESSION['user_id'];
$dj_id = $_POST['dj_id'];
$event_date = $_POST['event_date'];

// Validate event date
$current_date = date('Y-m-d');
if ($event_date < $current_date) {
    $response_message = "Error: Event date cannot be before the current date.";
} else {
    // Check if the DJ is already booked on the selected date
    $stmt_check = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE dj_id = ? AND event_date = ?");
    $stmt_check->bind_param("is", $dj_id, $event_date);
    $stmt_check->execute();
    $stmt_check->bind_result($booking_count);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($booking_count > 0) {
        $response_message = "Error: The selected DJ is already booked on this date.";
    } else {
        // Insert booking into the database
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, dj_id, event_date, status) VALUES (?, ?, ?, 'Pending')");
        $stmt->bind_param("iis", $user_id, $dj_id, $event_date);

        if ($stmt->execute()) {
            // Get the user's email
            $stmt_user = $conn->prepare("SELECT email FROM users WHERE user_id = ?");
            $stmt_user->bind_param("i", $user_id);
            $stmt_user->execute();
            $stmt_user->bind_result($user_email);
            $stmt_user->fetch();
            $stmt_user->close();

            // Get the DJ's name
            $stmt_dj = $conn->prepare("SELECT name FROM djs WHERE dj_id = ?");
            $stmt_dj->bind_param("i", $dj_id);
            $stmt_dj->execute();
            $stmt_dj->bind_result($dj_name);
            $stmt_dj->fetch();
            $stmt_dj->close();

            // Send email notification to the user using PHPMailer
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
                $mail->SMTPAuth = true;
                $mail->Username = ''; // SMTP username
                $mail->Password = ''; // SMTP password (ensure you use the correct password)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                //Recipients
                $mail->setFrom('no-reply@yourdomain.com', 'DJ Booking System');
                $mail->addAddress($user_email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Booking Confirmation';
                $mail->Body    = "Dear user,<br><br>Your booking for DJ $dj_name on $event_date has been set as pending. We will contact you for confirmation.<br><br>Thank you for using our service!";

                $mail->send();
                $response_message = 'Booking created successfully and email sent.';
            } catch (Exception $e) {
                $response_message = "Booking created successfully, but email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $response_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Response</title>
    <script>
        function showAlert(message) {
            alert(message);
            window.location.href = 'index.php'; // Redirect back to the main page after closing the alert
        }
    </script>
</head>
<body onload="showAlert('<?php echo $response_message; ?>')">
</body>
</html>

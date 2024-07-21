<?php
include 'db.php';

// IMAP server configuration
$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
$username = '';
$password = '';

// Try to connect
$inbox = imap_open($hostname, $username, $password) or die('Cannot connect to mail server: ' . imap_last_error());

// Search for unread emails
$emails = imap_search($inbox, 'UNSEEN');

if ($emails) {
    foreach ($emails as $email_number) {
        $overview = imap_fetch_overview($inbox, $email_number, 0);
        $message = imap_fetchbody($inbox, $email_number, 1);

        // Convert message to lower case for easier parsing
        $message = strtolower($message);

        // Extract booking ID from the message
        if (preg_match('/booking id is: (\d+)/i', $message, $matches)) {
            $booking_id = $matches[1];

            // Determine if the user confirmed or canceled the booking
            if (strpos($message, 'confirm') !== false) {
                $status = 'Confirmed';
            } elseif (strpos($message, 'cancel') !== false) {
                $status = 'Cancelled';
            } else {
                continue;
            }

            // Update the booking status in the database
            $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE booking_id = ?");
            $stmt->bind_param("si", $status, $booking_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Close the connection
imap_close($inbox);

$conn->close();
?>

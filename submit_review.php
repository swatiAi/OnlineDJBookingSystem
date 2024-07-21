<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$dj_id = $_POST['dj_id'];
$rating = $_POST['rating'];
$review_text = $_POST['review_text'];

$stmt = $conn->prepare("INSERT INTO reviews (user_id, dj_id, rating, review_text) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $user_id, $dj_id, $rating, $review_text);

if ($stmt->execute()) {
    echo "Review submitted successfully.";
    header("Location: index.php");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "User successfully deleted.";
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
    }
    $stmt->close();

    header("Location: admin.php");
    exit();
}
?>

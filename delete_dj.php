<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_dj']) && isset($_POST['token'])) {
    if ($_POST['token'] == $_SESSION['token']) {
        $dj_id = $_POST['dj_id'];

        $stmt = $conn->prepare("DELETE FROM djs WHERE dj_id = ?");
        $stmt->bind_param("i", $dj_id);
        if ($stmt->execute()) {
            $_SESSION['message'] = "DJ successfully deleted.";
        } else {
            $_SESSION['error'] = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Invalid token.";
    }
    header("Location: admin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $dj_id = $_GET['id'];
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete DJ</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Delete DJ</h2>
        <?php if (isset($_SESSION['error'])) { echo "<p style='color: red;'>{$_SESSION['error']}</p>"; unset($_SESSION['error']); } ?>
        <form action="delete_dj.php" method="post">
            <p>Are you sure you want to delete this DJ? This action cannot be undone.</p>
            <input type="hidden" name="dj_id" value="<?php echo htmlspecialchars($dj_id); ?>">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token']); ?>">
            <button type="submit" name="delete_dj">Delete DJ</button>
        </form>
        <p><a href="admin.php">Cancel</a></p>
    </div>
</body>
</html>

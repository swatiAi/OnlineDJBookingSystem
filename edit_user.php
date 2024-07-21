<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT name, email, is_admin FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($name, $email, $is_admin);
    $stmt->fetch();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, is_admin = ? WHERE user_id = ?");
    $stmt->bind_param("ssii", $name, $email, $is_admin, $user_id);

    if ($stmt->execute()) {
        header("Location: admin.php");
    } else {
        $error = "Error: " . $stmt->error;
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
    <title>Edit User</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Edit User</h2>
        <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        <form action="edit_user.php" method="post">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            <label for="is_admin">Admin:</label>
            <input type="checkbox" id="is_admin" name="is_admin" <?php echo $is_admin ? 'checked' : ''; ?>>
            <button type="submit">Update User</button>
        </form>
        <p><a href="admin.php">Back to Admin Dashboard</a></p>
    </div>
</body>
</html>

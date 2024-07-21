<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $genre = $_POST['genre'];
    $availability = $_POST['availability'];
    $bio = $_POST['bio'];
    $picture = '';

    if (!empty($_FILES["picture"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["picture"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
                $picture = $target_file;
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error = "File is not an image.";
        }
    }

    $stmt = $conn->prepare("INSERT INTO djs (name, genre, availability, bio, picture) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $genre, $availability, $bio, $picture);

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
    <title>Add DJ</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Add New DJ</h2>
        <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        <form action="add_dj.php" method="post" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre" required>
            <label for="availability">Availability:</label>
            <input type="text" id="availability" name="availability" required>
            <label for="bio">Bio:</label>
            <textarea id="bio" name="bio" required></textarea>
            <label for="picture">Picture:</label>
            <input type="file" name="picture" id="picture" required>
            <button type="submit">Add DJ</button>
        </form>
        <p><a href="admin.php">Back to Admin Dashboard</a></p>
    </div>
</body>
</html>

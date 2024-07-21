<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $dj_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT name, genre, availability, picture, bio FROM djs WHERE dj_id = ?");
    $stmt->bind_param("i", $dj_id);
    $stmt->execute();
    $stmt->bind_result($name, $genre, $availability, $picture, $bio);
    $stmt->fetch();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dj_id = $_POST['dj_id'];
    $name = $_POST['name'];
    $genre = $_POST['genre'];
    $availability = $_POST['availability'];
    $bio = $_POST['bio'];
    $picture = $_POST['existing_picture'];

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

    $stmt = $conn->prepare("UPDATE djs SET name = ?, genre = ?, availability = ?, bio = ?, picture = ? WHERE dj_id = ?");
    $stmt->bind_param("sssssi", $name, $genre, $availability, $bio, $picture, $dj_id);

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
    <title>Edit DJ</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Edit DJ</h2>
        <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        <form action="edit_dj.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="dj_id" value="<?php echo htmlspecialchars($dj_id); ?>">
            <input type="hidden" name="existing_picture" value="<?php echo htmlspecialchars($picture); ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($genre); ?>" required>
            <label for="availability">Availability:</label>
            <input type="text" id="availability" name="availability" value="<?php echo htmlspecialchars($availability); ?>" required>
            <label for="bio">Bio:</label>
            <textarea id="bio" name="bio" required><?php echo htmlspecialchars($bio); ?></textarea>
            <label for="picture">Picture:</label>
            <input type="file" name="picture" id="picture">
            <?php if (!empty($picture)) { ?>
                <img src="<?php echo htmlspecialchars($picture); ?>" alt="DJ Picture" style="width: 150px; height: 150px;">
            <?php } ?>
            <button type="submit">Update DJ</button>
        </form>
        <p><a href="admin.php">Back to Admin Dashboard</a></p>
    </div>
</body>
</html>

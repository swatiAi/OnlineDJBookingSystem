<?php
include 'db.php';

if (isset($_GET['dj_id'])) {
    $dj_id = $_GET['dj_id'];

    $stmt = $conn->prepare("SELECT name, genre, availability, picture, bio FROM djs WHERE dj_id = ?");
    $stmt->bind_param("i", $dj_id);
    $stmt->execute();
    $stmt->bind_result($name, $genre, $availability, $picture, $bio);
    $stmt->fetch();
    $stmt->close();

    echo "<div>
            <img src='" . htmlspecialchars($picture) . "' alt='DJ Picture' style='width:150px; height:150px; border-radius:50%; object-fit:cover;'>
            <h2>" . htmlspecialchars($name) . "</h2>
            <p><strong>Genre:</strong> " . htmlspecialchars($genre) . "</p>
            <p><strong>Availability:</strong> " . htmlspecialchars($availability) . "</p>
            <p><strong>Bio:</strong> " . nl2br(htmlspecialchars($bio)) . "</p>
          </div>";
} else {
    echo "No DJ selected.";
}

$conn->close();
?>

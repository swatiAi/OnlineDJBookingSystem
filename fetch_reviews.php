<?php
include 'db.php';

// Fetch reviews and calculate average rating for each DJ
$sql = "SELECT d.dj_id, d.name, d.genre, d.availability, AVG(r.rating) as avg_rating
        FROM djs d
        LEFT JOIN reviews r ON d.dj_id = r.dj_id
        GROUP BY d.dj_id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='review-item'>
                <h3>" . htmlspecialchars($row['name']) . " (" . number_format($row['avg_rating'], 1) . "/5)</h3>
                <p>Genre: " . htmlspecialchars($row['genre']) . "</p>
                <p>Availability: " . htmlspecialchars($row['availability']) . "</p>
                <h4>Reviews:</h4>";

        // Fetch individual reviews for the DJ
        $stmt = $conn->prepare("SELECT u.name as user_name, r.rating, r.review_text, r.created_at 
                                FROM reviews r
                                JOIN users u ON r.user_id = u.user_id
                                WHERE r.dj_id = ?");
        $stmt->bind_param("i", $row['dj_id']);
        $stmt->execute();
        $reviews_result = $stmt->get_result();

        if ($reviews_result->num_rows > 0) {
            while ($review = $reviews_result->fetch_assoc()) {
                echo "<div class='individual-review'>
                        <p><strong>" . htmlspecialchars($review['user_name']) . ":</strong> " . htmlspecialchars($review['review_text']) . " (" . $review['rating'] . "/5) on " . $review['created_at'] . "</p>
                      </div>";
            }
        } else {
            echo "<p>No reviews yet.</p>";
        }
        $stmt->close();
        echo "</div>";
    }
} else {
    echo "<p>No DJs found.</p>";
}

$conn->close();
?>

<?php
include 'db.php';

$genre = isset($_GET['genre']) ? $_GET['genre'] : '';
$availability = isset($_GET['availability']) ? $_GET['availability'] : '';

$sql = "SELECT d.dj_id, d.name, d.genre, d.availability, IFNULL(AVG(r.rating), 0) as avg_rating, d.picture 
        FROM djs d
        LEFT JOIN reviews r ON d.dj_id = r.dj_id 
        WHERE 1=1";

if (!empty($genre)) {
    $sql .= " AND d.genre LIKE '%" . $conn->real_escape_string($genre) . "%'";
}

if (!empty($availability)) {
    $sql .= " AND d.availability LIKE '%" . $conn->real_escape_string($availability) . "%'";
}

$sql .= " GROUP BY d.dj_id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td><img src='" . htmlspecialchars($row['picture']) . "' alt='DJ Picture' style='width:100px; height:100px; border-radius:50%; object-fit:cover;' onclick='showModal(" . htmlspecialchars($row['dj_id']) . ")'></td>
                <td><a href='javascript:void(0);' onclick='showModal(" . htmlspecialchars($row['dj_id']) . ")'>" . htmlspecialchars($row['name']) . "</a></td>
                <td>" . htmlspecialchars($row['genre']) . "</td>
                <td>" . htmlspecialchars($row['availability']) . "</td>
                <td>" . number_format($row['avg_rating'], 1) . "</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No DJs found</td></tr>";
}

$conn->close();
?>

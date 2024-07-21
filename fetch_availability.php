<?php
include 'db.php';

$sql = "SELECT dj_id, available_date FROM dj_availability";
$result = $conn->query($sql);

$events = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = [
            'title' => 'DJ ' . $row['dj_id'],
            'start' => $row['available_date']
        ];
    }
}

$conn->close();

echo json_encode($events);
?>

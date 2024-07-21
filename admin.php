<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

// Fetch all DJs
$sql_djs = "SELECT * FROM djs";
$result_djs = $conn->query($sql_djs);

// Fetch all bookings
$sql_bookings = "SELECT b.booking_id, u.name AS user_name, d.name AS dj_name, b.event_date, b.status 
                 FROM bookings b 
                 JOIN users u ON b.user_id = u.user_id 
                 JOIN djs d ON b.dj_id = d.dj_id";
$result_bookings = $conn->query($sql_bookings);

// Fetch all users
$sql_users = "SELECT user_id, name, email, is_admin FROM users";
$result_users = $conn->query($sql_users);

// Fetch admin details from session
$admin_name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Admin';
$admin_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .dj-table img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this DJ? This action cannot be undone.');
        }

        function confirmDeleteUser() {
            return confirm('Are you sure you want to delete this user? This action cannot be undone.');
        }
    </script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Admin Dashboard</h1>
            <nav>
                <ul>
                    <li><a href="admin.php">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
            <div class="admin-info">
                <p>Logged in as: <strong><?php echo htmlspecialchars($admin_name); ?></strong> (<?php echo htmlspecialchars($admin_email); ?>)</p>
            </div>
        </header>

        <section class="dj-management">
            <h2>Manage DJs</h2>
            <table class="dj-table">
                <thead>
                    <tr>
                        <th>Picture</th>
                        <th>Name</th>
                        <th>Genre</th>
                        <th>Availability</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_djs->fetch_assoc()) { ?>
                        <tr>
                            <td>
                                <?php if ($row['picture']) { ?>
                                <img src="<?php echo htmlspecialchars($row['picture']); ?>" alt="DJ Picture">
                                <?php } else { ?>
                                <img src="default.png" alt="No Image">
                                <?php } ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['genre']); ?></td>
                            <td><?php echo htmlspecialchars($row['availability']); ?></td>
                            <td>
                                <a href="edit_dj.php?id=<?php echo $row['dj_id']; ?>">Edit</a> |
                                <a href="delete_dj.php?id=<?php echo $row['dj_id']; ?>" onclick="return confirmDelete();">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <a href="add_dj.php">Add New DJ</a>
        </section>

        <section class="booking-management">
            <h2>Manage Bookings</h2>
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>DJ</th>
                        <th>Event Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_bookings->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['dj_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['event_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td>
                                <a href="edit_booking.php?id=<?php echo $row['booking_id']; ?>">Edit</a> |
                                <a href="delete_booking.php?id=<?php echo $row['booking_id']; ?>" onclick="return confirm('Are you sure you want to delete this booking? This action cannot be undone.');">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>

        <section class="user-management">
            <h2>Manage Users</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_users->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo $row['is_admin'] ? 'Admin' : 'User'; ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $row['user_id']; ?>">Edit</a> |
                                <a href="delete_user.php?id=<?php echo $row['user_id']; ?>" onclick="return confirmDeleteUser();">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
<?php
$conn->close();
?>

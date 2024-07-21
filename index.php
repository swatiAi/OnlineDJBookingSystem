<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DJ Booking System</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="fullcalendar/main.css">
    <script src="fullcalendar/main.js"></script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Welcome to the DJ Booking System</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="booking_history.php">Booking History</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <?php
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }

        include 'db.php';
        $user_id = $_SESSION['user_id'];

        // Fetch user details
        $stmt = $conn->prepare("SELECT name, email FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($name, $email);
        $stmt->fetch();
        $stmt->close();

        if (isset($_SESSION['error_message'])) {
            echo "<p style='color: red;'>{$_SESSION['error_message']}</p>";
            unset($_SESSION['error_message']);
        }
        ?>

        <div class="user-info">
            <p>Logged in as: <strong><?php echo htmlspecialchars($name); ?></strong> (<?php echo htmlspecialchars($email); ?>)</p>
        </div>
        
        <main>
            <section class="dj-search">
                <h2>Search and Filter DJs</h2>
                <form id="searchForm" method="GET" action="index.php">
                    <label for="genre">Genre:</label>
                    <input type="text" id="genre" name="genre" placeholder="Enter genre">

                    <label for="availability">Availability:</label>
                    <input type="text" id="availability" name="availability" placeholder="Enter availability (e.g., Lahore)">

                    <button type="submit">Search</button>
                </form>
            </section>

            <section class="dj-list">
                <h2>Available DJs</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Picture</th>
                            <th>Name</th>
                            <th>Genre</th>
                            <th>Availability</th>
                            <th>Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php include 'search_djs.php'; ?>
                    </tbody>
                </table>
            </section>

            <section class="booking-form">
                <h2>Book a DJ</h2>
                <form id="bookingForm" action="book.php" method="post" onsubmit="return validateForm()">
                    <label for="dj_id">Choose a DJ:</label>
                    <select id="dj_id" name="dj_id" required>
                        <?php include 'fetch_djs_dropdown.php'; ?>
                    </select>
                    <label for="event_date">Event Date:</label>
                    <input type="date" id="event_date" name="event_date" required>
                    <button type="submit">Book Now</button>
                </form>
                <p id="formFeedback" style="color: red; display: none;"></p>
            </section>

            <section class="review-form">
                <h2>Leave a Review</h2>
                <form id="reviewForm" action="submit_review.php" method="post">
                    <label for="dj_id_review">Choose a DJ:</label>
                    <select id="dj_id_review" name="dj_id" required>
                        <?php include 'fetch_djs_dropdown.php'; ?>
                    </select>
                    <label for="rating">Rating (1-5):</label>
                    <input type="number" id="rating" name="rating" min="1" max="5" required>
                    <label for="review_text">Review:</label>
                    <textarea id="review_text" name="review_text" required></textarea>
                    <button type="submit">Submit Review</button>
                </form>
            </section>

            <section class="reviews">
                <h2>Reviews</h2>
                <?php include 'fetch_reviews.php'; ?>
            </section>
        </main>
    </div>

    <!-- The Modal -->
    <div id="djModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="djDetails"></div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: 'fetch_availability.php'
        });

        calendar.render();
    });

    function validateForm() {
        var dj = document.getElementById("dj_id").value;
        var date = document.getElementById("event_date").value;
        var feedback = document.getElementById("formFeedback");

        var currentDate = new Date().toISOString().split('T')[0]; // Get current date in YYYY-MM-DD format

        if (!dj || !date) {
            feedback.style.display = "block";
            feedback.textContent = "Please fill out all fields.";
            return false;
        }

        if (date < currentDate) {
            feedback.style.display = "block";
            feedback.textContent = "Event date cannot be before the current date.";
            return false;
        }

        feedback.style.display = "none";
        return true;
    }

    // Modal functionality
    function showModal(djId) {
        var modal = document.getElementById("djModal");
        var span = document.getElementsByClassName("close")[0];
        var djDetails = document.getElementById("djDetails");

        // Fetch DJ details via AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_dj_details.php?dj_id=' + djId, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                djDetails.innerHTML = xhr.responseText;
                modal.style.display = "block";
            } else {
                djDetails.innerHTML = "Error loading DJ details.";
            }
        };
        xhr.send();

        // Close the modal when the user clicks on <span> (x)
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Close the modal when the user clicks anywhere outside of the modal
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }
    </script>
</body>
</html>

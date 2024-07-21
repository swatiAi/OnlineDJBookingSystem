<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, name, email, password, is_admin FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $name, $email, $hashed_password, $is_admin);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['name'] = $name; // Set the name in the session
        $_SESSION['email'] = $email; // Set the email in the session
        $_SESSION['is_admin'] = $is_admin;
        if ($is_admin) {
            header("Location: admin.php");
        } else {
            header("Location: index.php");
        }
    } else {
        $error = "Invalid email or password.";
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
    <title>DJ Booking System</title>
    <link href="https://fonts.googleapis.com/css2?family=Muli:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./style-with-prefix.css">
    <style>
        .srouce{
            text-align: center;
            color: #ffffff;
            padding: 10px;
        }
    </style>
</head>
<body>

    <div class="main-container">
        <div class="form-container">



            <div class="form-body">
                <h2 class="title">Online DJ Booking System</h2>
                <br><br>

                <div class="_or">Login</div>
                <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
                <form action="login.php" method="post" class="the-form">

                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter your email">

                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password">

                    <input type="submit" value="Login">

                </form>
                <br>
                <div class="form-footer">Don't have an account?</div> <a href="register.php">Sign Up!</a>

            </div><!-- FORM BODY-->
        </div><!-- FORM CONTAINER -->
    </div>

</body>
</html>

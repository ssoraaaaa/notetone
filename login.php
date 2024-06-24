<?php
include('db.php');
session_start();

// Initialize error messages array
$errors = [];

// Display success message if it exists
$success_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate username and password: both fields are required
    if (empty($username)) {
        $errors[] = "Username is required";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    // Check credentials in the database
    if (empty($errors)) {
        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Start session and redirect to dashboard
                $_SESSION['username'] = $username;
                echo '<script>window.location.href="dashboard.php";</script>';
                exit;
            } else {
                $errors[] = "Incorrect username or password";
            }
        } else {
            $errors[] = "Incorrect username or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .error { color: red; }
    </style>
</head>
<body>
    <ul class="header">
        <a href="./index.html" ><img src="logo-gray.png" class="header_logo"></a>
        <li class="li_header"><a class="a_header" href="./login.php">Log in</a></li>
        <li class="li_header"><a class="a_header" href="./register.php">Register</a></li>
        <li class="li_header"><a class="a_header">About us</a></li>
    </ul>
    <div class="wrapper">
        <h2>Log in</h2>
        <?php if (!empty($success_message)): ?>
            <script>alert('<?php echo $success_message; ?>');</script>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <div class="input-box">
                <input type="text" name="username" placeholder="username">
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="password">
            </div>
            <div class="error-messages">
                <?php
                if (!empty($errors)) {
                    echo '<ul class="error">';
                    foreach ($errors as $error) {
                        echo '<li>' . htmlspecialchars($error, ENT_QUOTES) . '</li>';
                    }
                    echo '</ul>';
                }
                ?>
            </div>
            <button type="submit" class="btn">Log in</button>
            <div class="register-link">
                <p>Don't have an account? <a href="register.php">Register</a></p>
            </div>
        </form>
    </div>
</body>
</html>

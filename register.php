<?php
include('db.php');

// Initialize error messages array
$errors = [];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_repeat = $_POST['password_repeat'];

    // Validate username: only letters, numbers, hyphens, and underscores; at least 3 characters
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (!preg_match('/^[a-zA-Z0-9_-]{3,}$/', $username)) {
        $errors[] = "Username must be at least 3 characters and contain only letters, numbers, hyphens, or underscores";
    }

    // Validate password: only letters, numbers, and symbols; at least 8 characters
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (!preg_match('/^[\w\W]{8,}$/', $password)) {
        $errors[] = "Password must be at least 8 characters and contain only letters, numbers, or symbols";
    }

    // Validate repeated password ???????????????????????????????????????????????????
    if ($password !== $password_repeat) {
        $errors[] = "Passwords do not match";
    }

    // Check if username already exists
    if (empty($errors)) {
        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $errors[] = "User already exists";
        }
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
        
        if ($conn->query($sql) === TRUE) {
            echo '<script>window.location.href="login.php";</script>';
            exit;
        } else {
            $errors[] = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        <h2>Register</h2>
        <form method="POST" action="register.php">
            <div class="input-box">
                <input type="text" name="username" placeholder="username" autofocus>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="password">
            </div>
            <div class="input-box">
                <input type="password" name="password_repeat" placeholder="repeat password">
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
            <button type="submit" class="btn">Register</button>
            <div class="register-link">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </form>
    </div>
    
</body>
</html>

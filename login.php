<?php
session_start();
include('db.php');

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
    // Sanitize and validate inputs
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) { // Allow only alphanumeric and underscore
        $errors[] = "Invalid username format";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    }

    // Check credentials in the database if no errors
    if (empty($errors)) {
        try {
            // Use prepared statements for SQL injection prevention
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            if ($stmt === false) {
                throw new Exception("Database query failed.");
            }

            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    session_regenerate_id(true);

                    $_SESSION['username'] = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
                    $_SESSION['userid'] = $user['userid'];
                    echo '<script>window.location.href="dashboard.php";</script>';
                    exit;
                } else {
                    $errors[] = "Incorrect username or password";
                }
            } else {
                $errors[] = "Incorrect username or password";
            }
            $stmt->close();
        } catch (Exception $e) {
            // Log error for further analysis (ensure this path is secured and monitored)
            error_log("Database error: " . $e->getMessage());
            $errors[] = "An unexpected error occurred. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <ul class="header">
        <a href="./index.html"><img src="logo-gray.png" class="header_logo"></a>
        <li class="li_header"><a class="a_header" href="./login.php">Log in</a></li>
        <li class="li_header"><a class="a_header" href="./register.php">Register</a></li>
        <li class="li_header"><a class="a_header">About us</a></li>
    </ul>
    <div class="wrapper">
        <h2>Log in</h2>
        <?php if (!empty($success_message)): ?>
            <script>alert('<?php echo htmlspecialchars($success_message, ENT_QUOTES); ?>');</script>
        <?php endif; ?>
        <form method="POST" saction="login.php">
            <div class="input-box">
                <input type="text" name="username" placeholder="username" autofocus>
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

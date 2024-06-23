<?php
session_start();
include 'db.php';

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $repeat_password = trim($_POST['repeat_password']);

    // Server-side validation
    if (!preg_match('/^[a-zA-Z0-9_!@#$%^&*]+$/', $username)) {
        $error_message = "Username can only contain letters, numbers, and symbols.";
    } elseif (strlen($username) < 3) {
        $error_message = "Username must be at least 3 characters long.";
    } elseif (!preg_match('/^[a-zA-Z0-9_!@#$%^&*]+$/', $password)) {
        $error_message = "Password can only contain letters, numbers, and symbols.";
    } elseif (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } elseif ($password !== $repeat_password) {
        $error_message = "Passwords do not match.";
    } else {
        $checkUser = "SELECT * FROM users WHERE username=?";
        $stmt = $conn->prepare($checkUser);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Username already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $error_message = "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notetone - Register</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script>
        function validateForm() {
            var username = document.forms["registerForm"]["username"].value;
            var password = document.forms["registerForm"]["password"].value;
            var repeatPassword = document.forms["registerForm"]["repeat_password"].value;
            var errorMessage = "";

            if (!/^[a-zA-Z0-9_!@#$%^&*]+$/.test(username)) {
                errorMessage += "Username can only contain letters, numbers, and symbols.\n";
            } else if (username.length < 3) {
                errorMessage += "Username must be at least 3 characters long.\n";
            }

            if (!/^[a-zA-Z0-9_!@#$%^&*]+$/.test(password)) {
                errorMessage += "Password can only contain letters, numbers, and symbols.\n";
            } else if (password.length < 8) {
                errorMessage += "Password must be at least 8 characters long.\n";
            }

            if (password !== repeatPassword) {
                errorMessage += "Passwords do not match.\n";
            }

            if (errorMessage) {
                alert(errorMessage);
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<ul class="header">
    <a href="./index.html"><img src="logo-gray.png" class="header_logo"></a>
    <li class="li_header"><a class="a_header" href="./login.php">Log in</a></li>
    <li class="li_header"><a class="a_header" href="./register.php">Register</a></li>
    <li class="li_header"><a class="a_header">About us</a></li>
</ul>
<div class="wrapper">
    <form name="registerForm" action="register.php" method="post" autocomplete="off" onsubmit="return validateForm()">
        <h2>Register</h2>
        <div class="input-box">
            <input type="text" name="username" placeholder="username" required>
        </div>
        <div class="input-box">
            <input type="password" name="password" placeholder="password" required>
        </div>
        <div class="input-box">
            <input type="password" name="repeat_password" placeholder="repeat password" required>
        </div>
        <button type="submit" class="btn">Register</button>
        <div class="register-link">
            <p>Have an account? <a href="login.php"> Login</a></p>
        </div>
        <?php
        if ($error_message != '') {
            echo '<p style="color:red;">' . $error_message . '</p>';
        }
        ?>
    </form>
</div>
</body>
</html>

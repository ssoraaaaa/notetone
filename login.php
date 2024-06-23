<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $hashed_password = $user['password'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['userid'] = $user['userid'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit();
        } else {
           // echo "Invalid username or password.";
        }
    } else {
      //  echo "Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notetone - Log in</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<ul class="header">
    <a href="./index.html"><img src="logo-gray.png" class="header_logo"></a>
    <li class="li_header"><a class="a_header" href="./login.php">Log in</a></li>
    <li class="li_header"><a class="a_header" href="./register.php">Register</a></li>
    <li class="li_header"><a class="a_header">About us</a></li>
</ul>
<div class="wrapper">
    <form action="login.php" method="post">
        <h2>Log in</h2>
        <div class="input-box">
            <input type="text" name="username" placeholder="username" required>
        </div>
        <div class="input-box">
            <input type="password" name="password" placeholder="password" required>
        </div>
        <div class="remember-me">
            <label><input type="checkbox">Remember me</label>
        </div>
        <button type="submit" class="btn">Log in</button>
        <div class="register-link">
            <p>Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </form>
</div>
</body>
</html>

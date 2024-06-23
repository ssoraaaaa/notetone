<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $checkUser = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($checkUser);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username already exists";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            echo "New record created successfully";
            header("Location: login.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $stmt->close();
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
</head>
<body>
<ul class="header">
    <a href="./index.html"><img src="logo-gray.png" class="header_logo"></a>
    <li class="li_header"><a class="a_header" href="./login.php">Log in</a></li>
    <li class="li_header"><a class="a_header" href="./register.php">Register</a></li>
    <li class="li_header"><a class="a_header">About us</a></li>
</ul>
<div class="wrapper">
    <form action="register.php" method="post" autocomplete="off">
        <h2>Register</h2>
        <div class="input-box">
            <input type="text" name="username" placeholder="username" required>
        </div>
        <div class="input-box">
            <input type="password" name="password" placeholder="password" required>
        </div>
        <div class="input-box">
            <input type="password" placeholder="repeat password" required>
        </div>
        <button type="submit" class="btn">Register</button>
        <div class="register-link">
            <p>Have an account? <a href="login.php"> Login</a></p>
        </div>
    </form>
</div>
</body>
</html>

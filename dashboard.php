<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <ul class="header">
        <a href="./dashboard.php"><img src="logo-gray.png" class="header_logo" alt="Logo"></a>
        <li class="li_header"><a class="a_header" href="dashboard.php">Dashboard</a></li>
        <li class="li_header"><a class="a_header" href="threads.php">Threads</a></li>
        <li class="li_header"><a class="a_header" href="notations.php">Notations</a></li>
        <li class="li_header"><a class="a_header" href="logout.php">Logout</a></li>
    </ul>
    <div class="wrapper">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p>This is your dashboard. Here you can manage your account and access exclusive content.</p>
        <div id="profile" class="section">
            <h3>Your Profile</h3>
            <p>Username: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <!-- Add more profile information as needed -->
        </div>
        <div id="notations" class="section">
            <h3>Your Notations</h3>
            <p>Manage your music notations here.</p>
            <button class="btn" onclick="location.href='add_notation.php'">Add Notation</button>
            <!-- List user notations here -->
            <ul>
                <!-- Example notation -->
                <li>Notation 1: [Link to notation]</li>
                <!-- Add more notations dynamically -->
            </ul>
        </div>
        <div id="threads" class="section">
            <h3>Threads</h3>
            <p>Participate in music-related threads.</p>
            <button class="btn" onclick="location.href='add_thread.php'">Start Thread</button>
            <!-- List thread threads here -->
            <ul>
                <!-- Example thread -->
                <li>Thread 1: [Link to thread]</li>
                <!-- Add more threads dynamically -->
            </ul>
        </div>
    </div>
</body>
</html>

<?php
session_start();
include('db.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Fetch user's threads
$username = $_SESSION['username'];
$userid = $_SESSION['userid'];
$thread_sql = "SELECT t.*, u.username AS user_name 
               FROM threads t 
               LEFT JOIN users u ON t.createdby = u.userid 
               WHERE t.createdby = '$userid'";
$thread_result = $conn->query($thread_sql);
$threads = [];
if ($thread_result->num_rows > 0) {
    while ($row = $thread_result->fetch_assoc()) {
        $threads[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Threads</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <ul class="header">
        <a href="dashboard.php"><img src="logo-gray.png" class="header_logo" alt="Logo"></a>
        <li class="li_header"><a class="a_header" href="dashboard.php">Dashboard</a></li>
        <li class="li_header"><a class="a_header" href="threads.php">My Threads</a></li>
        <li class="li_header"><a class="a_header" href="notations.php">My Notations</a></li>
        <li class="li_header"><a class="a_header" href="profile.php">Profile</a></li>
        <li class="li_header"><a class="a_header" href="logout.php">Logout</a></li>
    </ul>
    <div class="container">
        <div class="wrapper2">
            <h2>Your Threads</h2>
            <button class="btn" onclick="location.href='add_thread.php'">Add Thread</button>
            <div class="threads-container-dashboard">
                <?php foreach ($threads as $thread): ?>
                    <div class="box">
                        <a href="thread.php?id=<?php echo $thread['threadid']; ?>">
                            <p class="bolded"><?php echo htmlspecialchars($thread['title']); ?></p>
                            <p>Created by: <?php echo htmlspecialchars($thread['user_name']); ?></p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>

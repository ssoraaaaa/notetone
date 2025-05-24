<?php
session_start();
include('db.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Fetch all notations
$notation_sql = "SELECT n.*, s.title AS song_title, i.name AS instrument_name, IFNULL(u.username, 'deleted user') AS user_name 
                 FROM notations n 
                 LEFT JOIN songs s ON n.songid = s.songid 
                 LEFT JOIN instruments i ON n.instrumentid = i.instrumentid
                 LEFT JOIN users u ON n.userid = u.userid";
$notation_result = $conn->query($notation_sql);
$notations = [];
if ($notation_result->num_rows > 0) {
    while ($row = $notation_result->fetch_assoc()) {
        $notations[] = $row;
    }
}

// Fetch all threads
$thread_sql = "SELECT t.*, IFNULL(u.username, 'deleted user') AS user_name FROM threads t LEFT JOIN users u ON t.createdby = u.userid";
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
    <title>Dash</title>
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
    <div class="wrapper1">
        <div class="wrapper-left">
            <h2>All Notations</h2>
            <div class="notations-container-dashboard">
                <?php foreach ($notations as $notation): ?>
                    <div class="box">
                        <a href="notation.php?id=<?php echo $notation['notationid']; ?>">
                            <p class="bolded"><?php echo htmlspecialchars($notation['title']); ?></p>
                            <p>Song: <?php echo htmlspecialchars($notation['song_title']); ?></p>
                            <p>Instrument: <?php echo htmlspecialchars($notation['instrument_name']); ?></p>
                            <p>Added by: <?php echo htmlspecialchars($notation['user_name']); ?></p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="wrapper-right">
            <h2>All Threads</h2>
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

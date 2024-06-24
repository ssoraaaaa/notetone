<?php
session_start();
include('db.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Fetch all notations
$notation_sql = "SELECT * FROM notations";
$notation_result = $conn->query($notation_sql);
$notations = [];
if ($notation_result->num_rows > 0) {
    while ($row = $notation_result->fetch_assoc()) {
        $notations[] = $row;
    }
}

// Fetch all threads
$thread_sql = "SELECT * FROM threads";
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
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <ul class="header">
        <a href="dashboard.php"><img src="logo-gray.png" class="header_logo" alt="Logo"></a>
        <li class="li_header"><a class="a_header" href="dashboard.php">Dashboard</a></li>
        <li class="li_header"><a class="a_header" href="threads.php">Threads</a></li>
        <li class="li_header"><a class="a_header" href="notations.php">Notations</a></li>
        <li class="li_header"><a class="a_header" href="profile.php">Profile</a></li>
        <li class="li_header"><a class="a_header" href="logout.php">Logout</a></li>
    </ul>
    <div class="wrapper1">
        <div class="wrapper-left">
            <h2>All Notations</h2>
            <ul>
                <?php foreach ($notations as $notation): ?>
                    <li>
                        <a href="notation.php?id=<?php echo $notation['notationid']; ?>">
                            <?php echo htmlspecialchars($notation['content']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="wrapper-right">
            <h2>All Threads</h2>
            <ul>
                <?php foreach ($threads as $thread): ?>
                    <li>
                        <a href="thread_detail.php?id=<?php echo $thread['threadid']; ?>">
                            <?php echo htmlspecialchars($thread['title']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>

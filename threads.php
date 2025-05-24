<?php
session_start();
include('includes/db.php');

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
        <div class="wrapper" style="width: 80%; max-width: 1200px; margin: 0 auto;">
            <h2 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin-bottom: 30px; color: #fff; font-size: 2rem;">My Threads</h2>
            
            <?php if (empty($threads)): ?>
                <p style="color: #888; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1rem;">No threads found.</p>
            <?php else: ?>
                <?php foreach ($threads as $thread): ?>
                    <div style="background: #2a2a2a; border: 1px solid #464646; border-left: 5px solid #464646; padding: 20px; margin-bottom: 20px; border-radius: 4px;">
                        <h3 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0 0 10px 0; color: #fff; font-size: 1.5rem;"><?php echo htmlspecialchars($thread['title']); ?></h3>
                        <div style="background: #1a1a1a; padding: 15px; border-radius: 4px; margin-bottom: 15px;">
                            <p style="color: #fff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; font-size: 1rem;"><?php echo nl2br(htmlspecialchars($thread['content'])); ?></p>
                        </div>
                        <div style="color: #888; font-size: 0.9rem; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                            Created: <?php echo date('F j, Y', strtotime($thread['datecreated'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div style="margin-top: 30px;">
                <a href="add_thread.php" class="btn btn-primary" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-decoration: none; display: inline-block; text-align: center; line-height: 45px; height: 45px; font-size: 1rem;">Create New Thread</a>
            </div>
        </div>
    </div>
</body>
</html>

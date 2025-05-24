<?php
session_start();
include('includes/db.php');

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
    <title>Dashboard</title>
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
            <h2 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin-bottom: 30px; color: #fff; font-size: 2rem;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            
            <div style="display: flex; gap: 30px; margin-bottom: 50px;">
                <div style="flex: 1; background: #2a2a2a; border: 1px solid #464646; padding: 20px; border-radius: 4px;">
                    <h3 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin-bottom: 20px; color: #fff; font-size: 1.5rem;">Recent Threads</h3>
                    <?php if (empty($recent_threads)): ?>
                        <p style="color: #888; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1rem;">No recent threads found.</p>
                    <?php else: ?>
                        <?php foreach ($recent_threads as $thread): ?>
                            <div style="background: #1a1a1a; padding: 15px; border-radius: 4px; margin-bottom: 15px;">
                                <h4 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0 0 10px 0; color: #fff; font-size: 1.2rem;"><?php echo htmlspecialchars($thread['title']); ?></h4>
                                <p style="color: #888; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; font-size: 0.9rem;">
                                    By <?php echo htmlspecialchars($thread['user_name']); ?> on <?php echo date('F j, Y', strtotime($thread['datecreated'])); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div style="margin-top: 20px;">
                        <a href="threads.php" class="btn btn-primary" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-decoration: none; display: inline-block; text-align: center; line-height: 45px; height: 45px; font-size: 1rem;">View All Threads</a>
                    </div>
                </div>

                <div style="flex: 1; background: #2a2a2a; border: 1px solid #464646; padding: 20px; border-radius: 4px;">
                    <h3 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin-bottom: 20px; color: #fff; font-size: 1.5rem;">Recent Notations</h3>
                    <?php if (empty($recent_notations)): ?>
                        <p style="color: #888; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1rem;">No recent notations found.</p>
                    <?php else: ?>
                        <?php foreach ($recent_notations as $notation): ?>
                            <div style="background: #1a1a1a; padding: 15px; border-radius: 4px; margin-bottom: 15px;">
                                <h4 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0 0 10px 0; color: #fff; font-size: 1.2rem;"><?php echo htmlspecialchars($notation['title']); ?></h4>
                                <p style="color: #888; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; font-size: 0.9rem;">
                                    <?php echo htmlspecialchars($notation['song_title'] . ' - ' . $notation['performer']); ?> | 
                                    <?php echo htmlspecialchars($notation['instrument_name']); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div style="margin-top: 20px;">
                        <a href="notations.php" class="btn btn-primary" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-decoration: none; display: inline-block; text-align: center; line-height: 45px; height: 45px; font-size: 1rem;">View All Notations</a>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 30px;">
                <div style="flex: 1; background: #2a2a2a; border: 1px solid #464646; padding: 20px; border-radius: 4px;">
                    <h3 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin-bottom: 20px; color: #fff; font-size: 1.5rem;">Quick Actions</h3>
                    <div style="display: flex; gap: 15px;">
                        <a href="add_thread.php" class="btn btn-primary" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-decoration: none; display: inline-block; text-align: center; line-height: 45px; height: 45px; font-size: 1rem; flex: 1;">Create Thread</a>
                        <a href="add_notation.php" class="btn btn-primary" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-decoration: none; display: inline-block; text-align: center; line-height: 45px; height: 45px; font-size: 1rem; flex: 1;">Add Notation</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

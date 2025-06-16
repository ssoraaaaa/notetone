<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
if (!isset($_SESSION['userid'])) {
    // If userid is not set, try to fetch it from the database
    $sql = "SELECT userid FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $_SESSION['userid'] = $row['userid'];
        } else {
            die("Error: Could not find user ID");
        }
    } else {
        die("Error preparing statement: " . $conn->error);
    }
}

$userid = $_SESSION['userid'];
$thread_sql = "SELECT t.*, u.username AS user_name, u.moderatorstatus AS user_moderator FROM threads t LEFT JOIN users u ON t.createdby = u.userid WHERE t.createdby = ? ORDER BY t.threadid DESC";

$stmt = $conn->prepare($thread_sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $userid);
$stmt->execute();
$thread_result = $stmt->get_result();

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
    <title>My Threads - NoteTone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="navbar-spacer"></div>
    <div class="container">
        <div class="wrapper" style="width: 80%; max-width: 1200px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">My Threads</h1>
                <a href="add_thread.php?from=mythreads" class="btn btn-primary" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-decoration: none;">Create Thread</a>
            </div>
            <?php if (empty($threads)): ?>
                <p style="color: #888; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1rem;">No threads found.</p>
            <?php else: ?>
                <?php foreach ($threads as $thread): ?>
                    <a href="thread.php?id=<?php echo $thread['threadid']; ?>" style="text-decoration: none; color: inherit;">
                        <div style="background: #2a2a2a; border: 1px solid #464646; border-left: 5px solid #464646; padding: 20px; margin-bottom: 20px; border-radius: 4px; transition: box-shadow 0.2s;">
                            <h3 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0 0 10px 0; color: #fff; font-size: 1.5rem;">
                                <?php echo htmlspecialchars($thread['title']); ?>
                            </h3>
                            <div style="background: #1a1a1a; padding: 15px; border-radius: 4px; margin-bottom: 15px;">
                                <p style="color: #fff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; font-size: 1rem;">
                                    <?php echo nl2br(htmlspecialchars($thread['content'])); ?>
                                </p>
                            </div>
                            <div style="color: #888; font-size: 0.9rem; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                Created by: <?php 
                                    $is_admin = isset($thread['user_moderator']) && $thread['user_moderator'] == 1;
                                    $admin_symbol = $is_admin ? ' <span title="Admin" style="color:#ffcc00;">&#9812;</span>' : '';
                                    echo $thread['user_name'] ? htmlspecialchars($thread['user_name']) . $admin_symbol : '<em>deleted user</em>'; 
                                ?>
                                <?php if (isset($thread['datecreated'])): ?>
                                    <br>Created: <?php echo date('F j, Y', strtotime($thread['datecreated'])); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

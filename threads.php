<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

$thread_sql = "SELECT t.*, u.username AS user_name FROM threads t LEFT JOIN users u ON t.createdby = u.userid ORDER BY t.threadid DESC";
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
    <?php include 'includes/navbar.php'; ?>
    <div class="container">
        <div class="wrapper" style="width: 80%; max-width: 1200px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <h2 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #fff; font-size: 2rem; margin: 0;">Threads</h2>              
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
                                Created by: <?php echo $thread['user_name'] ? htmlspecialchars($thread['user_name']) : '<em>deleted user</em>'; ?>
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
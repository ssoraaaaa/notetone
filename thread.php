<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$thread_id = $_GET['id'];
$user_id = $_SESSION['userid'];

// Fetch the thread details
$thread_sql = "SELECT t.*, u.username AS user_name 
               FROM threads t 
               LEFT JOIN users u ON t.createdby = u.userid 
               WHERE t.threadid = '$thread_id'";
$thread_result = $conn->query($thread_sql);
$thread = $thread_result->fetch_assoc();

if (!$thread) {
    echo "Thread not found.";
    exit;
}

// Handle new comment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_comment'])) {
    $comment_content = trim($_POST['comment_content']);
    if ($comment_content !== '') {
        $insert_comment_sql = "INSERT INTO threadcomments (content, threadid, userid) VALUES ('" . $conn->real_escape_string($comment_content) . "', '$thread_id', '$user_id')";
        $conn->query($insert_comment_sql);
    }
}

// Handle deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    if ($thread['createdby'] == $user_id) {
        $delete_sql = "DELETE FROM threads WHERE threadid = '$thread_id'";
        if ($conn->query($delete_sql) === TRUE) {
            header('Location: threads.php');
            exit;
        } else {
            $error_message = 'Error: ' . $conn->error;
        }
    } else {
        $error_message = 'You do not have permission to delete this thread.';
    }
}

// Fetch comments for this thread
$comments_sql = "SELECT c.*, u.username FROM threadcomments c LEFT JOIN users u ON c.userid = u.userid WHERE c.threadid = '$thread_id' ORDER BY c.commentid ASC";
$comments_result = $conn->query($comments_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thread Details</title>
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
    <div class="wrapper-thread">
        <h2><?php echo htmlspecialchars($thread['title']); ?></h2>
        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <div class="thread-box">
            <p><?php echo nl2br(htmlspecialchars($thread['content'])); ?></p>
            <p><strong>Created by:</strong> <?php echo $thread['user_name'] ? htmlspecialchars($thread['user_name']) : '<em>deleted user</em>'; ?></p>
        </div>
        <?php if ($thread['createdby'] == $user_id): ?>
        <form method="POST" action="">
            <button class="btn-delete-thread" type="submit" name="delete">Delete Thread</button>
        </form>
        <?php endif; ?>
        <div class="comments-section">
            <h3>Replies</h3>
            <?php if ($comments_result && $comments_result->num_rows > 0): ?>
                <?php while ($comment = $comments_result->fetch_assoc()): ?>
                    <div class="comment-box">
                        <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                        <p><em>by <?php echo $comment['username'] ? htmlspecialchars($comment['username']) : 'deleted user'; ?></em></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No replies yet...</p>
            <?php endif; ?>
            <form method="POST" action="" class="comment-form">
                <textarea name="comment_content" placeholder="Add a comment..." required></textarea>
                <button type="submit" name="add_comment" class="btn">Post Reply</button>
            </form>
        </div>
    </div>
</body>
</html>

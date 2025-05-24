<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$thread_id = $_GET['id'];
$user_id = $_SESSION['userid'];

// Handle new comment or reply
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_comment'])) {
    $comment_content = trim($_POST['comment_content']);
    $reply_to = isset($_POST['replytocommentid']) && $_POST['replytocommentid'] !== '' ? intval($_POST['replytocommentid']) : 'NULL';
    if ($comment_content !== '') {
        $insert_comment_sql = "INSERT INTO threadcomments (content, threadid, userid, replytocommentid) VALUES ('" . $conn->real_escape_string($comment_content) . "', '$thread_id', '$user_id', " . ($reply_to === 'NULL' ? 'NULL' : $reply_to) . ")";
        $conn->query($insert_comment_sql);
    }
}

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

// Fetch all comments for this thread
$comments_sql = "SELECT c.*, u.username FROM threadcomments c LEFT JOIN users u ON c.userid = u.userid WHERE c.threadid = '$thread_id' ORDER BY c.commentid ASC";
$comments_result = $conn->query($comments_sql);
$comments = [];
if ($comments_result && $comments_result->num_rows > 0) {
    while ($row = $comments_result->fetch_assoc()) {
        $comments[] = $row;
    }
}
// Organize comments into a tree
function buildCommentTree($comments) {
    $tree = [];
    $refs = [];
    foreach ($comments as &$comment) {
        $comment['children'] = [];
        $refs[$comment['commentid']] = &$comment;
    }
    foreach ($comments as &$comment) {
        if ($comment['replytocommentid']) {
            $refs[$comment['replytocommentid']]['children'][] = &$comment;
        } else {
            $tree[] = &$comment;
        }
    }
    return $tree;
}
$comment_tree = buildCommentTree($comments);

// Get reply target if set
$reply_to_id = isset($_GET['reply']) ? intval($_GET['reply']) : '';
$reply_to_comment = null;
$reply_to_username = '';
if ($reply_to_id) {
    foreach ($comments as $c) {
        if ($c['commentid'] == $reply_to_id) {
            $reply_to_comment = $c;
            $reply_to_username = $c['username'] ? htmlspecialchars($c['username']) : 'deleted user';
            break;
        }
    }
}

function renderComments($comments, $reply_to_id) {
    foreach ($comments as $comment) {
        $is_reply_target = ($reply_to_id == $comment['commentid']);
        echo '<div class="comment-box" style="margin-left:'.($comment['replytocommentid'] ? '40' : '0').'px; background: #2a2a2a; padding: 15px; margin-bottom: 15px; border-radius: 8px;'.($comment['replytocommentid'] ? ' border-left: 3px solid #464646;' : '').' border: 1px solid #464646;">';
        echo '<p style="margin: 0 0 10px 0;"><em>' . ($comment['username'] ? htmlspecialchars($comment['username']) : 'deleted user') . ' replied:</em></p>';
        echo '<p'.($is_reply_target ? ' style="background:#464646;padding:10px;border-radius:4px;margin:0 0 10px 0;"' : ' style="margin:0 0 10px 0;"').'>' . nl2br(htmlspecialchars($comment['content'])) . '</p>';
        echo '<div style="clear: both;">';
        echo '<form method="get" style="display:inline;" class="reply-form" onsubmit="return false;">';
        echo '<input type="hidden" name="id" value="' . htmlspecialchars($_GET['id']) . '">';
        echo '<input type="hidden" name="reply" value="' . $comment['commentid'] . '">';
        echo '<button type="submit" class="btn btn-link p-0 reply-btn" data-comment-id="' . $comment['commentid'] . '" style="font-size: 0.85rem; border-radius: 10px; padding: 2px 8px; width: 80px;">Reply</button>';
        echo '</form>';
        echo '</div>';
        if (!empty($comment['children'])) {
            echo '<div style="margin-top: 20px;">';
            renderComments($comment['children'], $reply_to_id);
            echo '</div>';
        }
        echo '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($thread['title']); ?></title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <h2 style="text-align: center;"><?php echo htmlspecialchars($thread['title']); ?></h2>
        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <div class="thread-box">
            <p style="font-size: 1.2rem; line-height: 1.6;"><?php echo nl2br(htmlspecialchars($thread['content'])); ?></p>
            <p><strong>Created by:</strong> <?php echo $thread['user_name'] ? htmlspecialchars($thread['user_name']) : '<em>deleted user</em>'; ?></p>
        </div>
        <?php if ($thread['createdby'] == $user_id): ?>
        <form method="POST" action="">
            <button class="btn-delete-thread" type="submit" name="delete">Delete Thread</button>
        </form>
        <?php endif; ?>
        <div class="comments-section">
            <h3>Replies</h3>
            <?php renderComments($comment_tree, $reply_to_id); ?>
            <form method="POST" action="" class="comment-form mt-3" id="commentForm">
                <?php if ($reply_to_id && $reply_to_comment): ?>
                    <input type="hidden" name="replytocommentid" value="<?php echo $reply_to_id; ?>">
                    <div class="mb-3 d-flex align-items-center gap-2" style="background: #2a2a2a; padding: 10px; border-radius: 8px; margin-bottom: 10px;">
                        <span class="badge bg-primary">Replying to <?php echo $reply_to_username; ?></span>
                        <a href="javascript:void(0)" class="btn btn-secondary btn-sm ms-2" onclick="cancelReply()">Cancel</a>
                    </div>
                <?php endif; ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
                <textarea name="comment_content" placeholder="Add a comment..." required class="form-control" style="height: 100px; resize: none; width: 100%; background: #2a2a2a; color: #fff; border: 1px solid #464646; margin-bottom: 20px;"></textarea>
                <button type="submit" name="add_comment" class="btn btn-primary">Post Reply</button>
            </form>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Handle reply button clicks
        $('.reply-btn').click(function(e) {
            e.preventDefault();
            const commentId = $(this).data('comment-id');
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('reply', commentId);
            window.history.pushState({}, '', currentUrl);
            location.reload();
        });

        // Handle comment form submission
        $('#commentForm').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            $.ajax({
                url: 'add_comment.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.success) {
                        window.location.reload();
                    } else {
                        alert('Error posting comment: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Error posting comment. Please try again.');
                }
            });
        });
    });

    function cancelReply() {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.delete('reply');
        window.history.pushState({}, '', currentUrl);
        location.reload();
    }
    </script>
</body>
</html>

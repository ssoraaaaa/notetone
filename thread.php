<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

$thread_id = $_GET['id'];

// Determine the original referrer (not thread.php itself)
$original_referrer = '';
if (isset($_POST['original_referrer'])) {
    $original_referrer = $_POST['original_referrer'];
} elseif (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'thread.php') === false) {
    $original_referrer = $_SERVER['HTTP_REFERER'];
}

// Handle new comment or reply
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_comment'])) {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
    $comment_content = trim($_POST['comment_content']);
    $reply_to = isset($_POST['replytocommentid']) && $_POST['replytocommentid'] !== '' ? intval($_POST['replytocommentid']) : 'NULL';
    if ($comment_content !== '') {
        $insert_comment_sql = "INSERT INTO threadcomments (content, threadid, userid, replytocommentid) VALUES ('" . $conn->real_escape_string($comment_content) . "', '$thread_id', '" . $_SESSION['userid'] . "', " . ($reply_to === 'NULL' ? 'NULL' : $reply_to) . ")";
        $conn->query($insert_comment_sql);
    }
    // After posting, keep the original referrer
    if (!empty($original_referrer)) {
        $_SESSION['thread_original_referrer'] = $original_referrer;
    }
}

// Fetch the thread details
$thread_sql = "SELECT t.*, u.username AS user_name, u.moderatorstatus AS user_moderator FROM threads t LEFT JOIN users u ON t.createdby = u.userid WHERE t.threadid = '$thread_id'";
$thread_result = $conn->query($thread_sql);
$thread = $thread_result->fetch_assoc();

if (!$thread) {
    echo "Thread not found.";
    exit;
}

// Handle deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
    if ($thread['createdby'] == $_SESSION['userid']) {
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
$comments_sql = "SELECT c.*, u.username, u.moderatorstatus FROM threadcomments c LEFT JOIN users u ON c.userid = u.userid WHERE c.threadid = '$thread_id' ORDER BY c.commentid ASC";
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

// Handle comment deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_comment'])) {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
    $comment_id = intval($_POST['comment_id']);
    // Check if the comment belongs to the user
    $check_sql = "SELECT userid FROM threadcomments WHERE commentid = $comment_id";
    $check_result = $conn->query($check_sql);
    if ($check_result && $row = $check_result->fetch_assoc()) {
        if ($row['userid'] == $_SESSION['userid']) {
            $delete_sql = "DELETE FROM threadcomments WHERE commentid = $comment_id";
            $conn->query($delete_sql);
        } else {
            $error_message = 'You do not have permission to delete this comment.';
        }
    }
}

function renderComments($comments, $reply_to_id) {
    foreach ($comments as $comment) {
        $is_reply_target = ($reply_to_id == $comment['commentid']);
        $leftBorder = $is_reply_target ? '#fff' : '#464646';
        echo '<div class="comment-box" style="margin-left:'.($comment['replytocommentid'] ? '40' : '0').'px; background: #2a2a2a; border: 1px solid #464646; border-left: 5px solid '.$leftBorder.'; padding: 20px; margin-bottom: 20px; border-radius: 4px;">';
        echo '<div style="color: #888; font-size: 0.9rem; font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif; margin-bottom: 10px;">';
        // Username in comments (non-italic)
        $is_admin = isset($comment['moderatorstatus']) && $comment['moderatorstatus'] == 1;
        $admin_symbol = $is_admin ? ' <span title="Admin" style="color:#ffcc00;">&#9812;</span>' : '';
        echo '<span>' . ($comment['username'] ? htmlspecialchars($comment['username']) . $admin_symbol : 'deleted user') . '</span>';
        echo '</div>';
        echo '<div style="background: #1a1a1a; padding: 15px; border-radius: 4px; margin-bottom: 15px;">';
        echo '<p style="color: #fff; font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif; margin: 0; white-space: pre-wrap; word-break: break-word;">' . nl2br(htmlspecialchars($comment['content'])) . '</p>';
        echo '</div>';
        if (isLoggedIn()) {
            echo '<div style="clear: both; display: flex; gap: 10px;">';
            echo '<form method="get" style="display:inline;" class="reply-form" onsubmit="return false;">';
            echo '<input type="hidden" name="id" value="' . htmlspecialchars($_GET['id']) . '">';
            echo '<input type="hidden" name="reply" value="' . $comment['commentid'] . '">';
            echo '<button type="submit" class="btn btn-link p-0 reply-btn" data-comment-id="' . $comment['commentid'] . '" style="font-size: 0.85rem; border-radius: 10px; padding: 2px 8px; width: 80px;">Reply</button>';
            echo '</form>';
            // Show delete button if user is the author
            if (isset($_SESSION['userid']) && $comment['userid'] == $_SESSION['userid']) {
                echo '<form method="post" style="display:inline;" onsubmit="return confirm(\'Are you sure you want to delete this comment?\');">';
                echo '<input type="hidden" name="comment_id" value="' . $comment['commentid'] . '">';
                echo '<button type="submit" name="delete_comment" class="btn-delete-thread" style="font-size: 0.85rem; border-radius: 10px; padding: 2px 8px; margin-left: 8px; background: #ff6b6b; color: #fff;">Delete</button>';
                echo '</form>';
            }
            echo '</div>';
        }
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
    <title>Thread - NoteTone</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="navbar-spacer"></div>
    <div class="wrapper-thread">
        <?php 
            // Prefer the session referrer if set
            $back_url = 'threads.php';
            if (!empty($original_referrer)) {
                $back_url = htmlspecialchars($original_referrer);
            } elseif (isset($_SESSION['thread_original_referrer'])) {
                $back_url = htmlspecialchars($_SESSION['thread_original_referrer']);
            } elseif (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'thread.php') === false) {
                $back_url = htmlspecialchars($_SERVER['HTTP_REFERER']);
            }
        ?>
        <div style="margin-bottom: 20px;">
            <a href="<?php echo $back_url; ?>" class="btn btn-primary" style="text-decoration: none;">&larr; Back</a>
        </div>
        <h2 style="text-align: center;"><?php echo htmlspecialchars($thread['title']); ?></h2>
        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <div style="background: #2a2a2a; border: 1px solid #464646; border-left: 5px solid #464646; padding: 20px; margin-bottom: 20px; border-radius: 4px;">
            <div style="background: #1a1a1a; padding: 15px; border-radius: 4px; margin-bottom: 15px;">
                <p style="color: #fff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; font-size: 1rem;"><?php echo nl2br(htmlspecialchars($thread['content'])); ?></p>
            </div>
            <div style="color: #888; font-size: 0.9rem; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                Created by: <?php 
                    $is_admin = isset($thread['user_moderator']) && $thread['user_moderator'] == 1;
                    $admin_symbol = $is_admin ? ' <span title="Admin" style="color:#ffcc00;">&#9812;</span>' : '';
                    echo $thread['user_name'] ? htmlspecialchars($thread['user_name']) . $admin_symbol : '<em>deleted user</em>'; 
                ?>
            </div>
        </div>
        <?php if (isLoggedIn() && $thread['createdby'] == $_SESSION['userid']): ?>
        <form method="POST" action="">
            <button class="btn-delete-thread" type="submit" name="delete">Delete Thread</button>
        </form>
        <?php endif; ?>
        <div class="comments-section">
            <h3>Replies</h3>
            <?php renderComments($comment_tree, $reply_to_id); ?>
            <?php if (isLoggedIn()): ?>
            <form method="POST" action="" class="comment-form mt-3" id="commentForm">
                <?php if ($reply_to_id && $reply_to_comment): ?>
                    <input type="hidden" name="replytocommentid" value="<?php echo $reply_to_id; ?>">
                    <div class="mb-3 d-flex align-items-center justify-content-between" style="background: #2a2a2a; padding: 10px 15px; border-radius: 8px 8px 0 0; border-bottom: 1px solid #464646; position: relative;">
                        <span class="badge bg-primary" style="font-style: italic; color: #fff;">Replying to <?php 
                            $reply_is_admin = false;
                            foreach ($comments as $c) {
                                if ($c['commentid'] == $reply_to_id) {
                                    $reply_is_admin = isset($c['moderatorstatus']) && $c['moderatorstatus'] == 1;
                                    break;
                                }
                            }
                            echo $reply_to_username;
                            if ($reply_is_admin) echo ' <span title="Admin" style="color:#ffcc00;">&#9812;</span>';
                        ?></span>
                        <button type="button" onclick="cancelReply()" style="background: none; border: none; color: #888; font-size: 1.5rem; padding: 0 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 4px; transition: background-color 0.2s; position: absolute; right: 15px; top: 50%; transform: translateY(-50%);">×</button>
                    </div>
                <?php endif; ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
                <input type="hidden" name="original_referrer" value="<?php echo htmlspecialchars($original_referrer); ?>">
                <textarea name="comment_content" placeholder="Add a comment..." required class="form-control" style="resize: none; width: calc(100%); background: #2a2a2a; color: #fff; border: 1px solid #464646; margin-bottom: 20px; font-size: 1.1rem; padding: 15px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; outline: none;"></textarea>
                <button type="submit" name="add_comment" class="btn btn-primary">Post Reply</button>
            </form>
            <?php else: ?>
            <div style="background: #2a2a2a; border: 1px solid #464646; padding: 20px; border-radius: 4px; text-align: center;">
                <p style="color: #888; margin: 0;">Please <a href="login.php" style="color: #007bff; text-decoration: none;">login</a> to post a reply.</p>
            </div>
            <?php endif; ?>
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
                        location.reload();
                    } else {
                        alert(response.message || 'Error posting comment');
                    }
                },
                error: function() {
                    alert('Error posting comment');
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

    document.addEventListener('input', function(e) {
        if (e.target.tagName.toLowerCase() === 'textarea') {
            e.target.style.height = 'auto';
            e.target.style.height = e.target.scrollHeight + 'px';
            // Forcibly trigger reflow
            e.target.style.overflowY = 'hidden';
        }
    });
    </script>
</body>
</html>

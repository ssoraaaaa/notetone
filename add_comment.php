<?php
session_start();
include('includes/db.php');

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debug information
    error_log("POST data received: " . print_r($_POST, true));
    
    if (!isset($_POST['comment_content']) || !isset($_POST['id'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    $comment_content = trim($_POST['comment_content']);
    $thread_id = intval($_POST['id']);
    $user_id = $_SESSION['userid'];
    $reply_to = isset($_POST['replytocommentid']) && $_POST['replytocommentid'] !== '' ? intval($_POST['replytocommentid']) : 'NULL';

    if ($comment_content !== '') {
        $insert_comment_sql = "INSERT INTO threadcomments (content, threadid, userid, replytocommentid) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_comment_sql);
        
        if ($stmt) {
            $stmt->bind_param("siii", $comment_content, $thread_id, $user_id, $reply_to);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Comment cannot be empty']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?> 
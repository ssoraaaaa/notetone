<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Get the source page from URL parameter
$from = isset($_GET['from']) ? $_GET['from'] : 'threads';
$backLink = ($from === 'dashboard') ? 'dashboard.php' : 
           (($from === 'mythreads') ? 'mythreads.php' : 'threads.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $username = $_SESSION['username'];

    // Fetch user id based on username
    $user_sql = "SELECT userid FROM users WHERE username = ?";
    $stmt = $conn->prepare($user_sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_row = $result->fetch_assoc();
    $userid = $user_row['userid'];

    // Insert thread into database
    $sql = "INSERT INTO threads (title, content, createdby) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $content, $userid);
    
    if ($stmt->execute()) {
        header('Location: threads.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Thread - NoteTone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="navbar-spacer"></div>
    <div class="wrapper" style="width: 80%; max-width: 1200px; margin: 0 auto;">
        <h2 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">Start a New Thread</h2>
        <form method="POST" action="">
            <div class="form-group" style="width: 100%;">
                <input type="text" name="title" class="form-control" placeholder="Title" required style="background: #2a2a2a; color: #fff; border: 1px solid #464646; margin-bottom: 20px; width: 100%; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; height: 45px;">
            </div>
            <div class="form-group" style="width: 100%;">
                <textarea name="content" class="form-control" placeholder="What's on your mind?" required style="height: 100px; resize: none; width: 100%; background: #2a2a2a; color: #fff; border: 1px solid #464646; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;"></textarea>
            </div>
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" name="add_thread" class="btn btn-primary" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">Create Thread</button>
                <a href="<?php echo $backLink; ?>" class="btn btn-primary" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-decoration: none;">Back</a>
            </div>
        </form>
    </div>
</body>
</html>

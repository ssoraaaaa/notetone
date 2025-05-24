<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('includes/db.php');
    $title = $_POST['title'];
    $content = $_POST['content'];
    $username = $_SESSION['username'];

    // Fetch user id based on username
    $user_sql = "SELECT userid FROM users WHERE username='$username'";
    $user_result = $conn->query($user_sql);
    $user_row = $user_result->fetch_assoc();
    $userid = $user_row['userid'];

    // Insert thread into database
    $sql = "INSERT INTO threads (title, content, createdby) VALUES ('$title', '$content', '$userid')";
    if ($conn->query($sql) === TRUE) {
        header('Location: threads.php');
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Thread</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <ul class="header">
        <a href="<?php echo isset($_SESSION['username']) ? 'dashboard.php' : 'index.html'; ?>"><img src="logo-gray.png" class="header_logo" alt="Logo"></a>
        <li class="li_header"><a class="a_header" href="dashboard.php">Dashboard</a></li>
        <li class="li_header"><a class="a_header" href="threads.php">My Threads</a></li>
        <li class="li_header"><a class="a_header" href="notations.php">My Notations</a></li>
        <li class="li_header"><a class="a_header" href="profile.php">Profile</a></li>
        <li class="li_header"><a class="a_header" href="logout.php">Logout</a></li>
    </ul>
    <div class="wrapper">
        <h2>Start a New Thread</h2>
        <form method="POST" action="add_thread.php">
            <div class="input-box">
                <input type="text" name="title" placeholder="title">
            </div>
            <div class="input-box">
                <textarea name="content" placeholder="content"></textarea>
            </div>
            <button type="submit" class="btn">Start Thread</button>
        </form>
    </div>
</body>
</html>

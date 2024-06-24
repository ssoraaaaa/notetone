<?php
session_start();
include('db.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Fetch threads from the database
$sql = "SELECT * FROM threads";
$result = $conn->query($sql);
$threads = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
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
        <a href="<?php echo isset($_SESSION['username']) ? 'dashboard.php' : 'index.html'; ?>"><img src="logo-gray.png" class="header_logo" alt="Logo"></a>
        <li class="li_header"><a class="a_header" href="dashboard.php">Dashboard</a></li>
        <li class="li_header"><a class="a_header" href="threads.php">Threads</a></li>
        <li class="li_header"><a class="a_header" href="notations.php">Notations</a></li>
        <li class="li_header"><a class="a_header" href="logout.php">Logout</a></li>
    </ul>
    <div class="wrapper">
        <h2>Threads</h2>
        <button class="btn" onclick="location.href='add_thread.php'">Add Thread</button>
        <div class="section">
            <ul>
                <?php foreach ($threads as $thread): ?>
                    <li>
                        <a href="thread_detail.php?id=<?php echo $thread['threadid']; ?>">
                            <?php echo htmlspecialchars($thread['title']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>

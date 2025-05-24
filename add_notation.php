<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Fetch songs
$song_sql = "SELECT * FROM songs";
$song_result = $conn->query($song_sql);
$songs = [];
if ($song_result->num_rows > 0) {
    while ($row = $song_result->fetch_assoc()) {
        $songs[] = $row;
    }
}

// Fetch instruments
$instrument_sql = "SELECT * FROM instruments";
$instrument_result = $conn->query($instrument_sql);
$instruments = [];
if ($instrument_result->num_rows > 0) {
    while ($row = $instrument_result->fetch_assoc()) {
        $instruments[] = $row;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $songid = $_POST['songid'];
    $instrumentid = $_POST['instrumentid'];
    $userid = $_SESSION['userid'];
    $dateadded = date('Y-m-d');

    $sql = "INSERT INTO notations (title, dateadded, content, songid, instrumentid, userid) VALUES ('$title', '$dateadded', '$content', '$songid', '$instrumentid', '$userid')";
    if ($conn->query($sql) === TRUE) {
        header('Location: notations.php');
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
    <title>Add Notation</title>
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
    <div class="wrapper">
        <h2>Add Notation</h2>
        <form method="POST" action="add_notation.php">
            <div class="input-box">
                <input type="text" name="title" placeholder="Title" required>
            </div>
            <div class="input-box">
                <textarea name="content" placeholder="Content" required></textarea>
            </div>
            <div class="input-box">
                <select name="songid" required>
                    <option value="">Select Song</option>
                    <?php foreach ($songs as $song): ?>
                        <option value="<?php echo $song['songid']; ?>"><?php echo htmlspecialchars($song['title']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-box">
                <select name="instrumentid" required>
                    <option value="">Select Instrument</option>
                    <?php foreach ($instruments as $instrument): ?>
                        <option value="<?php echo $instrument['instrumentid']; ?>"><?php echo htmlspecialchars($instrument['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn">Add Notation</button>
        </form>
    </div>
</body>
</html>

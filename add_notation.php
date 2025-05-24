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
        <li class="li_header"><a class="a_header" href="threads.php">Threads</a></li>
        <li class="li_header"><a class="a_header" href="notations.php">Notations</a></li>
        <li class="li_header"><a class="a_header" href="mythreads.php">My Threads</a></li>
        <li class="li_header"><a class="a_header" href="mynotations.php">My Notations</a></li>
        <li class="li_header"><a class="a_header" href="profile.php">Profile</a></li>
        <li class="li_header"><a class="a_header" href="logout.php">Logout</a></li>
    </ul>
    <div class="wrapper" style="width: 80%; max-width: 1200px; margin: 0 auto;">
        <h2 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">Add Notation</h2>
        <form method="POST" action="">
            <div class="form-group" style="width: 100%;">
                <input type="text" name="title" class="form-control" placeholder="Title" required style="background: #2a2a2a; color: #fff; border: 1px solid #464646; margin-bottom: 20px; width: 100%; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; height: 45px;">
            </div>
            <div class="form-group" style="width: 100%;">
                <textarea name="content" class="form-control" placeholder="Enter your notation here..." required style="height: 200px; resize: none; width: 100%; background: #2a2a2a; color: #fff; border: 1px solid #464646; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;"></textarea>
            </div>
            <div class="form-group" style="width: 100%; margin-bottom: 20px;">
                <select name="songid" required style="width: 100%; height: 45px; background: #2a2a2a; color: #fff; border: 1px solid #464646; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 0 10px;">
                    <option value="">Select Song</option>
                    <?php foreach ($songs as $song): ?>
                        <option value="<?php echo $song['songid']; ?>"><?php echo htmlspecialchars($song['title'] . ' - ' . $song['performer']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="width: 100%; margin-bottom: 20px;">
                <select name="instrumentid" required style="width: 100%; height: 45px; background: #2a2a2a; color: #fff; border: 1px solid #464646; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 0 10px;">
                    <option value="">Select Instrument</option>
                    <?php foreach ($instruments as $instrument): ?>
                        <option value="<?php echo $instrument['instrumentid']; ?>"><?php echo htmlspecialchars($instrument['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin-top: 20px;">Add Notation</button>
        </form>
    </div>
</body>
</html>

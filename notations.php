<?php
session_start();
include('db.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_SESSION['userid'])) {
    echo "User ID not set in session.";
    exit;
}

// Fetch user's notations
$username = $_SESSION['username'];
$sql = "SELECT n.*, s.title AS song_title, i.name AS instrument_name 
        FROM notations n 
        LEFT JOIN songs s ON n.songid = s.songid 
        LEFT JOIN instruments i ON n.instrumentid = i.instrumentid 
        WHERE n.userid = (SELECT userid FROM users WHERE username='$username')";
$result = $conn->query($sql);
$notations = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notations[] = $row;
    }
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

// Initialize error and success messages
$song_error_message = '';
$song_success_message = '';
$instrument_error_message = '';
$instrument_success_message = '';

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_song'])) {
        $title = $_POST['title'];
        $performer = $_POST['performer'];
        $userid = $_SESSION['userid'];

        // Check for duplicate song
        $check_song_sql = "SELECT * FROM songs WHERE title='$title' AND performer='$performer'";
        $check_song_result = $conn->query($check_song_sql);
        if ($check_song_result->num_rows > 0) {
            $song_error_message = "Song with this title and performer already exists.";
        } else {
            $song_sql = "INSERT INTO songs (title, performer, userid) VALUES ('$title', '$performer', '$userid')";
            if ($conn->query($song_sql) === TRUE) {
                $song_success_message = "New song added successfully";
            } else {
                $song_error_message = "Error: " . $song_sql . "<br>" . $conn->error;
            }
        }
    } elseif (isset($_POST['add_instrument'])) {
        $name = $_POST['name'];
        $type = $_POST['type'];

        // Check for duplicate instrument
        $check_instrument_sql = "SELECT * FROM instruments WHERE name='$name'";
        $check_instrument_result = $conn->query($check_instrument_sql);
        if ($check_instrument_result->num_rows > 0) {
            $instrument_error_message = "Instrument with this name already exists.";
        } else {
            $instrument_sql = "INSERT INTO instruments (name, type) VALUES ('$name', '$type')";
            if ($conn->query($instrument_sql) === TRUE) {
                $instrument_success_message = "New instrument added successfully";
            } else {
                $instrument_error_message = "Error: " . $instrument_sql . "<br>" . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notations</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <ul class="header">
        <a href="dashboard.php"><img src="logo-gray.png" class="header_logo" alt="Logo"></a>
        <li class="li_header"><a class="a_header" href="dashboard.php">Dashboard</a></li>
        <li class="li_header"><a class="a_header" href="threads.php">Threads</a></li>
        <li class="li_header"><a class="a_header" href="notations.php">Notations</a></li>
        <li class="li_header"><a class="a_header" href="profile.php">Profile</a></li>
        <li class="li_header"><a class="a_header" href="logout.php">Logout</a></li>
    </ul>
    <div class="container">
        <div class="wrapper2">
            <h2>Your Notations</h2>
            <button class="btn" onclick="location.href='add_notation.php'">Add Notation</button>
            <div class="notations-container-notations">
                <?php foreach ($notations as $notation): ?>
                    <div class="notation-box">
                        <a href="notation.php?id=<?php echo $notation['notationid']; ?>">
                            <p><?php echo htmlspecialchars($notation['title']); ?></p>
                            <p>Song: <?php echo htmlspecialchars($notation['song_title']); ?></p>
                            <p>Instrument: <?php echo htmlspecialchars($notation['instrument_name']); ?></p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="right-container">
            <div class="wrapper3">
                <h2>Add a New Song</h2>
                <form method="POST" action="notations.php">
                    <div class="input-box">
                        <input type="text" name="title" placeholder="Song Title" required>
                    </div>
                    <div class="input-box">
                        <input type="text" name="performer" placeholder="Performer" required>
                    </div>
                    <button type="submit" name="add_song" class="btn">Add Song</button>
                    <?php if (!empty($song_error_message)): ?>
                        <div class="error"><?php echo $song_error_message; ?></div>
                    <?php endif; ?>
                    <?php if (!empty($song_success_message)): ?>
                        <div class="success"><?php echo $song_success_message; ?></div>
                    <?php endif; ?>
                </form>
            </div>
            <div class="wrapper4">
                <h2>Add a New Instrument</h2>
                <form method="POST" action="notations.php">
                    <div class="input-box">
                        <input type="text" name="name" placeholder="Instrument Name" required>
                    </div>
                    <div class="input-box">
                        <input type="text" name="type" placeholder="Instrument Type" required>
                    </div>
                    <button type="submit" name="add_instrument" class="btn">Add Instrument</button>
                    <?php if (!empty($instrument_error_message)): ?>
                        <div class="error"><?php echo $instrument_error_message; ?></div>
                    <?php endif; ?>
                    <?php if (!empty($instrument_success_message)): ?>
                        <div class="success"><?php echo $instrument_success_message; ?></div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

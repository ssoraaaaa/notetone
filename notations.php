<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Fetch user's notations
$username = $_SESSION['username'];
$userid = $_SESSION['userid'];
$notation_sql = "SELECT n.*, s.title AS song_title, i.name AS instrument_name 
                 FROM notations n 
                 LEFT JOIN songs s ON n.songid = s.songid 
                 LEFT JOIN instruments i ON n.instrumentid = i.instrumentid 
                 WHERE n.userid = '$userid'";
$notation_result = $conn->query($notation_sql);
$notations = [];
if ($notation_result->num_rows > 0) {
    while ($row = $notation_result->fetch_assoc()) {
        $notations[] = $row;
    }
}

// Handle adding a new song
$song_error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_song'])) {
    $song_title = $_POST['song_title'];
    $performer = $_POST['performer'];

    // Check for duplicates
    $duplicate_check = "SELECT * FROM songs WHERE title = '$song_title' AND performer = '$performer'";
    $duplicate_result = $conn->query($duplicate_check);

    if ($duplicate_result->num_rows > 0) {
        $song_error = 'This song already exists in the database.';
    } else {
        $song_sql = "INSERT INTO songs (title, performer, userid) VALUES ('$song_title', '$performer', '$userid')";
        if ($conn->query($song_sql) === TRUE) {
            $song_error = 'New song added successfully';
        } else {
            $song_error = 'Error: ' . $song_sql . '<br>' . $conn->error;
        }
    }
}

// Handle adding a new instrument
$instrument_error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_instrument'])) {
    $instrument_name = $_POST['instrument_name'];
    $instrument_type = $_POST['instrument_type'];

    // Check for duplicates
    $duplicate_check = "SELECT * FROM instruments WHERE name = '$instrument_name'";
    $duplicate_result = $conn->query($duplicate_check);

    if ($duplicate_result->num_rows > 0) {
        $instrument_error = 'This instrument already exists in the database.';
    } else {
        $instrument_sql = "INSERT INTO instruments (name, type) VALUES ('$instrument_name', '$instrument_type')";
        if ($conn->query($instrument_sql) === TRUE) {
            $instrument_error = 'New instrument added successfully';
        } else {
            $instrument_error = 'Error: ' . $instrument_sql . '<br>' . $conn->error;
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
        <li class="li_header"><a class="a_header" href="threads.php">My Threads</a></li>
        <li class="li_header"><a class="a_header" href="notations.php">My Notations</a></li>
        <li class="li_header"><a class="a_header" href="profile.php">Profile</a></li>
        <li class="li_header"><a class="a_header" href="logout.php">Logout</a></li>
    </ul>
    <div class="container">
        <div class="wrapper2">
            <h2>Your Notations</h2>
            <button class="btn" onclick="location.href='add_notation.php'">Add Notation</button>
            <div class="notations-container-notations">
                <?php foreach ($notations as $notation): ?>
                    <div class="box">
                        <a href="notation.php?id=<?php echo $notation['notationid']; ?>">
                            <p class="bolded"><?php echo htmlspecialchars($notation['title']); ?></p>
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
                <?php if ($song_error): ?>
                    <p class="error"><?php echo $song_error; ?></p>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="input-box">
                        <input type="text" name="song_title" placeholder="Song Title">
                    </div>
                    <div class="input-box">
                        <input type="text" name="performer" placeholder="Performer">
                    </div>
                    <button class="btn" type="submit" name="add_song">Add Song</button>
                </form>
            </div>
            <div class="wrapper4">
                <h2>Add a New Instrument</h2>
                <?php if ($instrument_error): ?>
                    <p class="error"><?php echo $instrument_error; ?></p>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="input-box">
                        <input type="text" name="instrument_name" placeholder="Instrument Name">
                    </div>
                    <div class="input-box">
                        <input type="text" name="instrument_type" placeholder="Instrument Type">
                    </div>
                    <button class="btn" type="submit" name="add_instrument">Add Instrument</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

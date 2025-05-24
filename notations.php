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
$notation_sql = "SELECT n.*, s.title AS song_title, i.name AS instrument_name, s.performer 
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
        <div class="wrapper" style="width: 80%; max-width: 1200px; margin: 0 auto;">
            <h2 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin-bottom: 30px; color: #fff; font-size: 2rem;">My Notations</h2>
            
            <?php if (empty($notations)): ?>
                <p style="color: #888; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1rem;">No notations found.</p>
            <?php else: ?>
                <?php foreach ($notations as $notation): ?>
                    <div style="background: #2a2a2a; border: 1px solid #464646; border-left: 5px solid #464646; padding: 20px; margin-bottom: 20px; border-radius: 4px;">
                        <h3 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0 0 10px 0; color: #fff; font-size: 1.5rem;"><?php echo htmlspecialchars($notation['title']); ?></h3>
                        <p style="color: #888; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0 0 15px 0; font-size: 1rem;">
                            Song: <?php echo htmlspecialchars($notation['song_title'] . ' - ' . $notation['performer']); ?> | 
                            Instrument: <?php echo htmlspecialchars($notation['instrument_name']); ?>
                        </p>
                        <div style="background: #1a1a1a; padding: 15px; border-radius: 4px; margin-bottom: 15px;">
                            <pre style="color: #fff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; white-space: pre-wrap; font-size: 1rem;"><?php echo htmlspecialchars($notation['content']); ?></pre>
                        </div>
                        <div style="color: #888; font-size: 0.9rem; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                            Created: <?php echo date('F j, Y', strtotime($notation['dateadded'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div style="margin-top: 30px;">
                <a href="add_notation.php" class="btn btn-primary" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-decoration: none; display: inline-block; text-align: center; line-height: 45px; height: 45px; font-size: 1rem;">Add New Notation</a>
            </div>

            <div style="margin-top: 50px; display: flex; gap: 30px;">
                <div style="flex: 1; background: #2a2a2a; border: 1px solid #464646; padding: 20px; border-radius: 4px;">
                    <h2 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin-bottom: 20px; color: #fff; font-size: 1.5rem;">Add a New Song</h2>
                    <?php if ($song_error): ?>
                        <p style="color: #ff6b6b; margin-bottom: 15px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1rem;"><?php echo $song_error; ?></p>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div style="margin-bottom: 15px;">
                            <input type="text" name="song_title" placeholder="Song Title" required style="width: 100%; height: 45px; background: #1a1a1a; color: #fff; border: 1px solid #464646; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 0 10px; font-size: 1rem;">
                        </div>
                        <div style="margin-bottom: 20px;">
                            <input type="text" name="performer" placeholder="Performer" required style="width: 100%; height: 45px; background: #1a1a1a; color: #fff; border: 1px solid #464646; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 0 10px; font-size: 1rem;">
                        </div>
                        <button type="submit" name="add_song" class="btn btn-primary" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1rem;">Add Song</button>
                    </form>
                </div>

                <div style="flex: 1; background: #2a2a2a; border: 1px solid #464646; padding: 20px; border-radius: 4px;">
                    <h2 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin-bottom: 20px; color: #fff; font-size: 1.5rem;">Add a New Instrument</h2>
                    <?php if ($instrument_error): ?>
                        <p style="color: #ff6b6b; margin-bottom: 15px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1rem;"><?php echo $instrument_error; ?></p>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div style="margin-bottom: 15px;">
                            <input type="text" name="instrument_name" placeholder="Instrument Name" required style="width: 100%; height: 45px; background: #1a1a1a; color: #fff; border: 1px solid #464646; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 0 10px; font-size: 1rem;">
                        </div>
                        <div style="margin-bottom: 20px;">
                            <input type="text" name="instrument_type" placeholder="Instrument Type" required style="width: 100%; height: 45px; background: #1a1a1a; color: #fff; border: 1px solid #464646; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 0 10px; font-size: 1rem;">
                        </div>
                        <button type="submit" name="add_instrument" class="btn btn-primary" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1rem;">Add Instrument</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

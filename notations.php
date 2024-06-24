<?php
session_start();
include('db.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Fetch user's notations
$username = $_SESSION['username'];
$sql = "SELECT * FROM notations WHERE userid = (SELECT userid FROM users WHERE username='$username')";
$result = $conn->query($sql);
$notations = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notations[] = $row;
    }
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_song'])) {
        // Add new song
        $title = $_POST['title'];
        $performer = $_POST['performer'];
        $userid = $_SESSION['userid'];

        $song_sql = "INSERT INTO songs (title, performer, userid) VALUES ('$title', '$performer', '$userid')";
        if ($conn->query($song_sql) === TRUE) {
            echo "New song added successfully";
        } else {
            echo "Error: " . $song_sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['add_instrument'])) {
        // Add new instrument
        $name = $_POST['name'];
        $type = $_POST['type'];

        $instrument_sql = "INSERT INTO instruments (name, type) VALUES ('$name', '$type')";
        if ($conn->query($instrument_sql) === TRUE) {
            echo "New instrument added successfully";
        } else {
            echo "Error: " . $instrument_sql . "<br>" . $conn->error;
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
    <div class="wrapper">
        <h2>Your Notations</h2>
        <button class="btn" onclick="location.href='add_notation.php'">Add Notation</button>
        <div class="section">
            <ul>
                <?php foreach ($notations as $notation): ?>
                    <li>
                        <a href="notation.php?id=<?php echo $notation['notationid']; ?>">
                            <?php echo htmlspecialchars($notation['content']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="section">
            <h2>Add a New Song</h2>
            <form method="POST" action="notations.php">
                <div class="input-box">
                    <input type="text" name="title" placeholder="Song Title" required>
                </div>
                <div class="input-box">
                    <input type="text" name="performer" placeholder="Performer" required>
                </div>
                <button type="submit" name="add_song" class="btn">Add Song</button>
            </form>
        </div>
        <div class="section">
            <h2>Add a New Instrument</h2>
            <form method="POST" action="notations.php">
                <div class="input-box">
                    <input type="text" name="name" placeholder="Instrument Name" required>
                </div>
                <div class="input-box">
                    <input type="text" name="type" placeholder="Instrument Type" required>
                </div>
                <button type="submit" name="add_instrument" class="btn">Add Instrument</button>
            </form>
        </div>
    </div>
</body>
</html>

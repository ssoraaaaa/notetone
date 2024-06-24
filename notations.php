<?php
session_start();
include('db.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Fetch notations from the database
$sql = "SELECT * FROM notations";
$result = $conn->query($sql);
$notations = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notations[] = $row;
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
        <a href="<?php echo isset($_SESSION['username']) ? 'dashboard.php' : 'index.html'; ?>"><img src="logo-gray.png" class="header_logo" alt="Logo"></a>
        <li class="li_header"><a class="a_header" href="dashboard.php">Dashboard</a></li>
        <li class="li_header"><a class="a_header" href="threads.php">Threads</a></li>
        <li class="li_header"><a class="a_header" href="notations.php">Notations</a></li>
        <li class="li_header"><a class="a_header" href="logout.php">Logout</a></li>
    </ul>
    <div class="wrapper">
        <h2>Notations</h2>
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
    </div>
</body>
</html>

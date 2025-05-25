<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Fetch user's notations
$username = $_SESSION['username'];
$userid = $_SESSION['userid'];
$notation_sql = "SELECT n.*, s.title AS song_title, i.name AS instrument_name, s.performer FROM notations n LEFT JOIN songs s ON n.songid = s.songid LEFT JOIN instruments i ON n.instrumentid = i.instrumentid WHERE n.userid = '$userid' ORDER BY n.notationid DESC";
$notation_result = $conn->query($notation_sql);
$notations = [];
if ($notation_result->num_rows > 0) {
    while ($row = $notation_result->fetch_assoc()) {
        $notations[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Notations - NoteTone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="container">
        <div class="wrapper" style="width: 80%; max-width: 1200px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <h2 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #fff; font-size: 2rem; margin: 0;">My Notations</h2>
                <a href="add_notation.php" class="btn btn-primary" style="text-decoration: none;">Add New Notation</a>
            </div>
            <?php if (empty($notations)): ?>
                <p style="color: #888; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1rem;">No notations found.</p>
            <?php else: ?>
                <?php foreach ($notations as $notation): ?>
                    <a href="notation.php?id=<?php echo $notation['notationid']; ?>" style="text-decoration: none; color: inherit;">
                        <div style="background: #2a2a2a; border: 1px solid #464646; border-left: 5px solid #464646; padding: 20px; margin-bottom: 20px; border-radius: 4px; transition: box-shadow 0.2s;">
                            <h3 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0 0 10px 0; color: #fff; font-size: 1.5rem;">
                                <?php echo htmlspecialchars($notation['title']); ?>
                            </h3>
                            <p style="color: #888; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0 0 15px 0; font-size: 1rem;">
                                Song: <?php echo htmlspecialchars($notation['song_title'] . ' - ' . $notation['performer']); ?> |
                                Instrument: <?php echo htmlspecialchars($notation['instrument_name']); ?>
                            </p>
                            <div style="color: #888; font-size: 0.9rem; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                Created: <?php echo date('F j, Y', strtotime($notation['dateadded'])); ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 
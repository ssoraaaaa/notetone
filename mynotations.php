<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Fetch user's notations
$username = $_SESSION['username'];
if (!isset($_SESSION['userid'])) {
    // If userid is not set, try to fetch it from the database
    $sql = "SELECT userid FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $_SESSION['userid'] = $row['userid'];
        } else {
            die("Error: Could not find user ID");
        }
    } else {
        die("Error preparing statement: " . $conn->error);
    }
}

$userid = $_SESSION['userid'];
$notation_sql = "SELECT n.*, s.title AS song_title, i.name AS instrument_name, s.performer
                 FROM notations n 
                 LEFT JOIN songs s ON n.songid = s.songid 
                 LEFT JOIN instruments i ON n.instrumentid = i.instrumentid 
                 WHERE n.userid = ? 
                 ORDER BY n.notationid DESC";

$stmt = $conn->prepare($notation_sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $userid);
$stmt->execute();
$notation_result = $stmt->get_result();
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
    <div class="navbar-spacer"></div>
    <div class="container">
        <div class="wrapper" style="width: 80%; max-width: 1200px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <h2 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #fff; font-size: 2rem; margin: 0;">My Notations</h2>
                <a href="edit.php?from=notations" class="btn btn-primary" style="text-decoration: none;">Create Notation</a>
            </div>
            <?php if (empty($notations)): ?>
                <p style="color: #888; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1rem;">No notations found.</p>
            <?php else: ?>
                <?php foreach ($notations as $notation): ?>
                    <a href="notation.php?id=<?php echo $notation['notationid']; ?>&from=mynotations&back=mynotations.php" style="text-decoration: none; color: inherit; display: block;">
                        <div style="background: #2a2a2a; border: 1px solid #464646; border-left: 5px solid #464646; padding: 20px; margin-bottom: 20px; border-radius: 4px; transition: box-shadow 0.2s; display: flex; justify-content: space-between; align-items: center; position: relative;">
                            <div style="flex: 1;">
                                <h3 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0 0 10px 0; color: #fff; font-size: 1.5rem; display: inline-block;">
                                    <?php echo htmlspecialchars($notation['title']); ?>
                                </h3>
                                <p style="color: #888; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0 0 15px 0; font-size: 1rem;">
                                    <?php echo htmlspecialchars($notation['song_title'] . ' - ' . $notation['performer']); ?>
                                </p>
                                <p style="color: #888; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0 0 15px 0; font-size: 1rem;">
                                    for <?php echo htmlspecialchars($notation['instrument_name']); ?>
                                </p>
                                <div style="color: #888; font-size: 0.9rem; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                    Created: <?php echo date('F j, Y', strtotime($notation['dateadded'])); ?>
                                </div>
                            </div>
                            <div style="margin-left: 20px; z-index: 2; position: relative;">
                                <a href="edit.php?id=<?php echo $notation['notationid']; ?>&from=mynotations&back=mynotations.php" class="btn btn-primary" style="text-decoration: none; padding: 8px 16px;">Edit</a>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 
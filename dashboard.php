<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Fetch all notations
$notation_sql = "SELECT n.*, s.title AS song_title, i.name AS instrument_name, IFNULL(u.username, 'deleted user') AS user_name, u.moderatorstatus AS user_moderator FROM notations n LEFT JOIN songs s ON n.songid = s.songid AND s.status = 'approved' LEFT JOIN instruments i ON n.instrumentid = i.instrumentid LEFT JOIN users u ON n.userid = u.userid";
$notation_result = $conn->query($notation_sql);
$notations = [];
if ($notation_result->num_rows > 0) {
    while ($row = $notation_result->fetch_assoc()) {
        $notations[] = $row;
    }
}

// Fetch all threads
$thread_sql = "SELECT t.*, IFNULL(u.username, 'deleted user') AS user_name, u.moderatorstatus AS user_moderator FROM threads t LEFT JOIN users u ON t.createdby = u.userid";
$thread_result = $conn->query($thread_sql);
$threads = [];
if ($thread_result->num_rows > 0) {
    while ($row = $thread_result->fetch_assoc()) {
        $threads[] = $row;
    }
}

// Limit to 5 for dashboard display
$notations = array_slice($notations, 0, 3);
$threads = array_slice($threads, 0, 4);

// Fetch pending songs for admin
$is_admin = false;
if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];
    $admin_check = $conn->query("SELECT moderatorstatus FROM users WHERE userid = '$user_id'");
    $is_admin = $admin_check && $admin_check->fetch_assoc()['moderatorstatus'] == 1;
}
$pending_songs = [];
if ($is_admin) {
    $pending_result = $conn->query("SELECT * FROM songs WHERE status = 'pending'");
    if ($pending_result && $pending_result->num_rows > 0) {
        while ($row = $pending_result->fetch_assoc()) {
            $pending_songs[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - NoteTone</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="navbar-spacer"></div>
    <div class="wrapper">
        <section class="container-header">
            <h2>Dashboard</h2>
        </section>
        <section class="quick-actions" style="background: #2a2a2a; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
            <h3 style="color: #fff; margin-bottom: 1rem;">Quick Actions</h3>
            <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
                <a href="add_song.php" class="btn btn-secondary" style="flex: 1; min-width: 200px; text-align: center;">
                    <span style="font-size: 1.5rem; display: block; margin-bottom: 0.5rem;"><i class="fas fa-music"></i></span>
                    Request a Song
                </a>
                <a href="add_thread.php" class="btn btn-secondary" style="flex: 1; min-width: 200px; text-align: center;">
                    <span style="font-size: 1.5rem; display: block; margin-bottom: 0.5rem;"><i class="fas fa-comments"></i></span>
                    Start a Discussion
                </a>
                <a href="mythreads.php" class="btn btn-secondary" style="flex: 1; min-width: 200px; text-align: center;">
                    <span style="font-size: 1.5rem; display: block; margin-bottom: 0.5rem;"><i class="fas fa-list"></i></span>
                    My Threads
                </a>
                <a href="profile.php" class="btn btn-secondary" style="flex: 1; min-width: 200px; text-align: center;">
                    <span style="font-size: 1.5rem; display: block; margin-bottom: 0.5rem;"><i class="fas fa-user"></i></span>
                    My Profile
                </a>
            </div>
        </section>
        <section class="dashboard-section">
            <div class="dashboard-grid" style="display: flex; flex-wrap: wrap; gap: 2rem;">
                <div style="flex: 1; min-width: 350px; max-width: 600px;">
                    <h3 style="color: #fff; margin-bottom: 1rem;">Recent Notations</h3>
                    <div class="notations-grid">
                        <?php foreach ($notations as $notation): ?>
                        <div class="notation-card">
                            <div class="notation-header">
                                <span class="genre-tag"><?php echo htmlspecialchars($notation['song_title'] ?? ''); ?></span>
                                <span class="notation-date"><?php echo isset($notation['dateadded']) ? date('M d, Y', strtotime($notation['dateadded'])) : ''; ?></span>
                            </div>
                            <h4><?php echo htmlspecialchars($notation['title']); ?></h4>
                            <p class="notation-author">by <?php echo htmlspecialchars($notation['user_name']); ?></p>
                            <?php if (!empty($notation['song_title'])): ?>
                                <p class="notation-song">Song: <?php echo htmlspecialchars($notation['song_title']); ?><?php if (!empty($notation['performer'])): ?> &ndash; <?php echo htmlspecialchars($notation['performer']); ?><?php endif; ?></p>
                            <?php endif; ?>
                            <?php if (!empty($notation['genre_names'])): ?>
                                <p class="notation-genres">Genres: <span class="genre-tag"><?php echo htmlspecialchars($notation['genre_names']); ?></span></p>
                            <?php endif; ?>
                            <a href="notation.php?id=<?php echo $notation['notationid']; ?>" class="btn btn-outline">View Notation</a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div style="text-align: right; margin-top: 1rem;">
                        <a href="notations.php" class="btn btn-primary">View More Notations</a>
                    </div>
                </div>
                <div style="flex: 1; min-width: 350px; max-width: 600px;">
                    <h3 style="color: #fff; margin-bottom: 1rem;">Recent Threads</h3>
                    <div class="threads-grid">
                        <?php foreach ($threads as $thread): ?>
                        <div class="thread-box">
                            <h4><?php echo htmlspecialchars($thread['title']); ?></h4>
                            <p class="thread-author">by <?php echo htmlspecialchars($thread['user_name']); ?></p>
                            <?php if (isset($thread['datecreated'])): ?>
                                <p class="thread-date" style="color: #888; font-size: 0.9rem; margin: 5px 0;">Created: <?php echo date('F j, Y', strtotime($thread['datecreated'])); ?></p>
                            <?php endif; ?>
                            <a href="thread.php?id=<?php echo $thread['threadid']; ?>" class="btn btn-outline">View Thread</a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div style="text-align: right; margin-top: 1rem;">
                        <a href="threads.php" class="btn btn-primary">View More Threads</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>

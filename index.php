<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

// Fetch recent notations
$recent_notations_query = "SELECT n.*, u.username, s.title AS song_title, s.performer, 
    GROUP_CONCAT(DISTINCT g.name ORDER BY g.name SEPARATOR ', ') AS genre_names
    FROM notations n 
    LEFT JOIN users u ON n.userid = u.userid 
    LEFT JOIN songs s ON n.songid = s.songid
    LEFT JOIN song_genres sg ON s.songid = sg.songid
    LEFT JOIN genres g ON sg.genreid = g.genreid
    GROUP BY n.notationid
    ORDER BY n.dateadded DESC LIMIT 3";
$recent_notations = $conn->query($recent_notations_query);

// Fetch popular genres
$genres_query = "SELECT g.*, COUNT(n.notationid) as notation_count 
                FROM genres g 
                LEFT JOIN song_genres sg ON g.genreid = sg.genreid
                LEFT JOIN songs s ON sg.songid = s.songid
                LEFT JOIN notations n ON s.songid = n.songid
                GROUP BY g.genreid 
                ORDER BY notation_count DESC LIMIT 4";
$genres = $conn->query($genres_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NoteTone - Your Musical Notation Platform</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <?php include 'components/account/success_modal.php'; ?>
    <div class="navbar-spacer"></div>
    
    <div class="wrapper">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <h1>Welcome to NoteTone</h1>
                <p class="hero-subtitle">Your platform for musical notation and discussion.</p>
                <div class="hero-buttons">
                    <a href="add_song.php" class="btn btn-primary">Log in Create Notation</a>
                    <a href="threads.php" class="btn btn-primary">Browse Discussions</a>
                </div>
            </div>
        </section>

        <!-- Quick Start Guide -->
        <section class="quick-start-section">
            <h2>Getting Started</h2>
            <div class="quick-start-grid" href="login.php">
                <div class="quick-start-card">
                    <i class="fas fa-music"></i>
                    <h3>Create Notation</h3>
                    <p>Share your musical ideas with our easy-to-use notation editor</p>
                </div>
                <div class="quick-start-card" href="login.php">
                    <i class="fas fa-comments"></i>
                    <h3>Join Discussions</h3>
                    <p>Engage with other musicians in our community threads</p>
                </div>
                <div class="quick-start-card" href="login.php">
                    <i class="fas fa-users"></i>
                    <h3>Connect</h3>
                    <p>Follow other musicians and build your network</p>
                </div>
            </div>
        </section>

        <!-- Recent Notations -->
        <section class="recent-notations-section">
            <h2>Recent Notations</h2>
            <div class="notations-grid">
                <?php while($notation = $recent_notations->fetch_assoc()): ?>
                <div class="notation-card">
                    <div class="notation-header">
                        <span class="genre-tag"><?php echo htmlspecialchars($notation['genre_names']); ?></span>
                        <span class="notation-date"><?php echo date('M d, Y', strtotime($notation['dateadded'])); ?></span>
                    </div>
                    <h3><?php echo htmlspecialchars($notation['title']); ?></h3>
                    <p class="notation-author">by <?php echo htmlspecialchars($notation['username']); ?></p>
                    <?php if (!empty($notation['song_title'])): ?>
                        <p class="notation-song">Song: <?php echo htmlspecialchars($notation['song_title']); ?><?php if (!empty($notation['performer'])): ?> &ndash; <?php echo htmlspecialchars($notation['performer']); ?><?php endif; ?></p>
                    <?php endif; ?>
                    <a href="notation.php?id=<?php echo $notation['notationid']; ?>" class="btn btn-outline">View Notation</a>
                </div>
                <?php endwhile; ?>
            </div>
        </section>

        <!-- Popular Genres -->
        <section class="genres-section">
            <h2>Popular Genres</h2>
            <div class="genres-grid">
                <?php while($genre = $genres->fetch_assoc()): ?>
                <div class="genre-card">
                    <h3><?php echo htmlspecialchars($genre['name']); ?></h3>
                    <p><?php echo $genre['notation_count']; ?> notations</p>
                    <a href="notations.php?genre=<?php echo $genre['genreid']; ?>" class="btn btn-outline">Browse Genre</a>
                </div>
                <?php endwhile; ?>
            </div>
        </section>

        <!-- Community Call-to-Action -->
        <section class="cta-section" >
            <div class="cta-content">
                <h2>Join Our Musical Community</h2>
                <p>Share your music, connect with other musicians, and grow together</p>
                <?php if(!isset($_SESSION['user_id'])): ?>
                    <div class="cta-buttons">
                        <a href="register.php" class="btn btn-primary">Sign Up Now</a>
                        <a href="login.php" class="btn btn-secondary">Login</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
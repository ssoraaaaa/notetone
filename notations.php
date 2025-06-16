<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

// Fetch all filter options
$songs = [];
$song_result = $conn->query("SELECT * FROM songs WHERE status = 'approved' ORDER BY title ASC");
if ($song_result && $song_result->num_rows > 0) {
    while ($row = $song_result->fetch_assoc()) {
        $songs[] = $row;
    }
}

$instruments = [];
$instrument_result = $conn->query("SELECT * FROM instruments ORDER BY name ASC");
if ($instrument_result && $instrument_result->num_rows > 0) {
    while ($row = $instrument_result->fetch_assoc()) {
        $instruments[] = $row;
    }
}

$genres = [];
$genre_result = $conn->query("SELECT * FROM genres ORDER BY name ASC");
if ($genre_result && $genre_result->num_rows > 0) {
    while ($row = $genre_result->fetch_assoc()) {
        $genres[] = $row;
    }
}

// Get filter parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$genre = isset($_GET['genre']) ? intval($_GET['genre']) : 0;
$song = isset($_GET['song']) ? intval($_GET['song']) : 0;
$instrument = isset($_GET['instrument']) ? intval($_GET['instrument']) : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';

// Build the query
$sql = "SELECT DISTINCT n.*, s.title AS song_title, s.performer, i.name AS instrument_name, u.username AS user_name, u.moderatorstatus AS user_moderator, 
    GROUP_CONCAT(DISTINCT g.name ORDER BY g.name SEPARATOR ', ') AS genre_names
    FROM notations n 
    LEFT JOIN songs s ON n.songid = s.songid 
    LEFT JOIN instruments i ON n.instrumentid = i.instrumentid 
    LEFT JOIN users u ON n.userid = u.userid 
    LEFT JOIN song_genres sg ON s.songid = sg.songid
    LEFT JOIN genres g ON sg.genreid = g.genreid
    WHERE 1=1";

$params = [];
$types = "";

if ($search) {
    $sql .= " AND (n.title LIKE ? OR s.title LIKE ? OR s.performer LIKE ? OR u.username LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ssss";
}

if ($genre) {
    $sql .= " AND sg.genreid = ?";
    $params[] = $genre;
    $types .= "i";
}

if ($song) {
    $sql .= " AND n.songid = ?";
    $params[] = $song;
    $types .= "i";
}

if ($instrument) {
    $sql .= " AND n.instrumentid = ?";
    $params[] = $instrument;
    $types .= "i";
}

// Add GROUP BY n.notationid
$sql .= " GROUP BY n.notationid";

// Add sorting
switch ($sort) {
    case 'title_asc':
        $sql .= " ORDER BY n.title ASC";
        break;
    case 'title_desc':
        $sql .= " ORDER BY n.title DESC";
        break;
    case 'date_asc':
        $sql .= " ORDER BY n.dateadded ASC";
        break;
    case 'date_desc':
    default:
        $sql .= " ORDER BY n.dateadded DESC";
        break;
}

// Prepare and execute the query
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$notation_result = $stmt->get_result();

$notations = [];
if ($notation_result && $notation_result->num_rows > 0) {
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
    <title>Notations</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="navbar-spacer"></div>
    <div class="wrapper">
        <section class="container-header">
            <h2>Notations</h2>
            <a href="edit.php?from=notations" class="btn btn-primary">Create Notation</a>
        </section>
        <!-- Search and Filter Form -->
        <section class="filter-section" style="background: #232323; padding: 24px; border-radius: 8px; margin-bottom: 32px;">
            <form method="GET" action="" class="search-form" style="display: flex; flex-direction: column; gap: 16px;">
                <div style="width: 100%;">
                    <input type="text" name="search" class="form-control" placeholder="Search notations, songs, performers, users..." value="<?php echo htmlspecialchars($search); ?>" style="width: 100%;">
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end; width: 100%;">
                    <div style="flex: 1; min-width: 160px;">
                        <select name="genre" class="form-control">
                            <option value="">All Genres</option>
                            <?php foreach ($genres as $g): ?>
                                <option value="<?php echo $g['genreid']; ?>" <?php echo $genre == $g['genreid'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($g['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="flex: 1; min-width: 160px;">
                        <select name="song" class="form-control">
                            <option value="">All Songs</option>
                            <?php foreach ($songs as $s): ?>
                                <option value="<?php echo $s['songid']; ?>" <?php echo $song == $s['songid'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($s['title'] . ' - ' . $s['performer']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="flex: 1; min-width: 160px;">
                        <select name="instrument" class="form-control">
                            <option value="">All Instruments</option>
                            <?php foreach ($instruments as $i): ?>
                                <option value="<?php echo $i['instrumentid']; ?>" <?php echo $instrument == $i['instrumentid'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($i['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="flex: 1; min-width: 160px;">
                        <select name="sort" class="form-control">
                            <option value="date_desc" <?php echo $sort == 'date_desc' ? 'selected' : ''; ?>>Newest First</option>
                            <option value="date_asc" <?php echo $sort == 'date_asc' ? 'selected' : ''; ?>>Oldest First</option>
                            <option value="title_asc" <?php echo $sort == 'title_asc' ? 'selected' : ''; ?>>Title A-Z</option>
                            <option value="title_desc" <?php echo $sort == 'title_desc' ? 'selected' : ''; ?>>Title Z-A</option>
                        </select>
                    </div>
                    <div style="flex: 1; min-width: 120px; display: flex; gap: 8px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Apply</button>
                        <a href="notations.php" class="btn btn-secondary" style="width: 100%; text-align: center;">Reset</a>
                    </div>
                </div>
            </form>
        </section>
        <section class="notations-section">
            <div class="notations-grid">
                <?php foreach ($notations as $notation): ?>
                <div class="notation-card">
                    <div class="notation-header">
                        <span class="genre-tag"><?php echo htmlspecialchars($notation['genre_names']); ?></span>
                        <span class="notation-date"><?php echo date('M d, Y', strtotime($notation['dateadded'])); ?></span>
                    </div>
                    <h3><?php echo htmlspecialchars($notation['title']); ?></h3>
                    <p class="notation-author">by <?php echo htmlspecialchars($notation['user_name']); ?></p>
                    <p class="notation-song">Song: <?php echo htmlspecialchars($notation['song_title']); ?><?php if (!empty($notation['performer'])): ?> &ndash; <?php echo htmlspecialchars($notation['performer']); ?><?php endif; ?></p>
                    <a href="notation.php?id=<?php echo $notation['notationid']; ?>" class="btn btn-outline">View Notation</a>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</body>
</html> 
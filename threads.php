<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

// Get filter parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$created_by = isset($_GET['created_by']) ? trim($_GET['created_by']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';

// Build the query
$sql = "SELECT DISTINCT t.*, u.username AS user_name, u.moderatorstatus AS user_moderator
    FROM threads t 
    LEFT JOIN users u ON t.createdby = u.userid 
    LEFT JOIN threadcomments tc ON t.threadid = tc.threadid
    WHERE 1=1";

$params = [];
$types = "";

if ($search) {
    $sql .= " AND (t.title LIKE ? OR t.content LIKE ? OR u.username LIKE ? OR tc.content LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ssss";
}

if ($created_by) {
    $sql .= " AND u.username LIKE ?";
    $params[] = "%$created_by%";
    $types .= "s";
}

// Add sorting
switch (
    $sort) {
    case 'title_asc':
        $sql .= " ORDER BY t.title ASC";
        break;
    case 'title_desc':
        $sql .= " ORDER BY t.title DESC";
        break;
    case 'date_asc':
        $sql .= " ORDER BY t.threadid ASC";
        break;
    case 'date_desc':
    default:
        $sql .= " ORDER BY t.threadid DESC";
        break;
}

// Prepare and execute the query
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$thread_result = $stmt->get_result();

$threads = [];
if ($thread_result && $thread_result->num_rows > 0) {
    while ($row = $thread_result->fetch_assoc()) {
        $threads[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Threads</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="navbar-spacer"></div>
    <div class="wrapper">
        <section class="container-header">
            <h2>Threads</h2>
            <a href="add_thread.php" class="btn btn-primary">Start New Thread</a>
        </section>
        <!-- Search and Filter Form -->
        <section class="filter-section" style="background: #232323; padding: 24px; border-radius: 8px; margin-bottom: 32px;">
            <form method="GET" action="" class="search-form" style="display: flex; flex-direction: column; gap: 16px;">
                <div style="width: 100%;">
                    <input type="text" name="search" class="form-control" placeholder="Search threads, content, users..." value="<?php echo htmlspecialchars($search); ?>" style="width: 100%;">
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end; width: 100%;">
                    <div style="flex: 1; min-width: 160px;">
                        <input type="text" name="created_by" class="form-control" placeholder="Created by..." value="<?php echo htmlspecialchars($created_by); ?>">
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
                        <a href="threads.php" class="btn btn-secondary" style="width: 100%; text-align: center;">Reset</a>
                    </div>
                </div>
            </form>
        </section>
        <section class="threads-section">
            <div class="threads-grid">
                <?php foreach ($threads as $thread): ?>
                <div class="thread-box">
                    <h3><?php echo htmlspecialchars($thread['title']); ?></h3>
                    <p class="thread-author">by <?php echo htmlspecialchars($thread['user_name']); ?></p>
                    <?php if (isset($thread['datecreated'])): ?>
                        <p class="thread-date" style="color: #888; font-size: 0.9rem; margin: 5px 0;">Created: <?php echo date('F j, Y', strtotime($thread['datecreated'])); ?></p>
                    <?php endif; ?>
                    <a href="thread.php?id=<?php echo $thread['threadid']; ?>" class="btn btn-outline">View Thread</a>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</body>
</html> 
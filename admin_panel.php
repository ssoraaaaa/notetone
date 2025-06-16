<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

// Only allow admins
if (!isLoggedIn() || !isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit();
}
$user_id = $_SESSION['userid'];
$admin_check = $conn->query("SELECT moderatorstatus FROM users WHERE userid = '$user_id'");
$is_admin = $admin_check && $admin_check->fetch_assoc()['moderatorstatus'] == 1;
if (!$is_admin) {
    header('Location: profile.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - NoteTone</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-nav { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 30px; }
        .admin-nav a { background: #232323; color:#e7dba9; padding: 10px 18px; border-radius: 4px; text-decoration: none; font-weight: bold; border: 1px solid #464646; }
        .admin-nav a:hover { background: #e7dba9; color: #232323; }
        .admin-section { background: #232323; border: 1px solid #464646; border-radius: 4px; padding: 24px; margin-bottom: 30px; }
        h2 { color: #e7dba9; }
    </style>
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="navbar-spacer"></div>
<div class="container">
    <div class="wrapper" style="max-width: 1500px; margin: 0 auto;">
    <h1 style="color:#e7dba9;">Admin Panel</h1>
        <div class="admin-nav">
            <a href="#stats">Statistics</a>
            <a href="#users">Users</a>
            <a href="#song-approval">Song Request Approval</a>
        </div>
        <div id="stats" class="admin-section">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <h2 style="margin: 0;">Website Statistics</h2>
                <button id="toggle-stats-btn" style="background: #232323; color: #e7dba9; border: 1px solid #464646; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; cursor: pointer; transition: background 0.3s;" title="Collapse/Expand Statistics">
                    <span id="toggle-stats-arrow" style="display: inline-block; transition: transform 0.3s;">&#9660;</span>
                </button>
            </div>
            <div id="stats-content">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                    <?php
                    // Total Users
                    $total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
                    // Total Songs
                    $total_songs = $conn->query("SELECT COUNT(*) as count FROM songs WHERE status = 'approved'")->fetch_assoc()['count'];
                    // Total Notations
                    $total_notations = $conn->query("SELECT COUNT(*) as count FROM notations")->fetch_assoc()['count'];
                    // Total Threads
                    $total_threads = $conn->query("SELECT COUNT(*) as count FROM threads")->fetch_assoc()['count'];
                    // Total Comments
                    $total_comments = $conn->query("SELECT COUNT(*) as count FROM threadcomments")->fetch_assoc()['count'];
                    // Pending Songs
                    $pending_songs = $conn->query("SELECT COUNT(*) as count FROM songs WHERE status = 'pending'")->fetch_assoc()['count'];
                    ?>
                    <div style="background: #181818; padding: 1.5rem; border-radius: 8px; text-align: center;">
                        <i class="fas fa-users" style="font-size: 2rem; color: #e7dba9; margin-bottom: 0.5rem;"></i>
                        <h3 style="color: #e7dba9; margin: 0.5rem 0;">Total Users</h3>
                        <p style="font-size: 1.5rem; color: #fff; margin: 0;"><?php echo $total_users; ?></p>
                    </div>
                    <div style="background: #181818; padding: 1.5rem; border-radius: 8px; text-align: center;">
                        <i class="fas fa-music" style="font-size: 2rem; color: #e7dba9; margin-bottom: 0.5rem;"></i>
                        <h3 style="color: #e7dba9; margin: 0.5rem 0;">Total Songs</h3>
                        <p style="font-size: 1.5rem; color: #fff; margin: 0;"><?php echo $total_songs; ?></p>
                    </div>
                    <div style="background: #181818; padding: 1.5rem; border-radius: 8px; text-align: center;">
                        <i class="fas fa-file-alt" style="font-size: 2rem; color: #e7dba9; margin-bottom: 0.5rem;"></i>
                        <h3 style="color: #e7dba9; margin: 0.5rem 0;">Total Notations</h3>
                        <p style="font-size: 1.5rem; color: #fff; margin: 0;"><?php echo $total_notations; ?></p>
                    </div>
                    <div style="background: #181818; padding: 1.5rem; border-radius: 8px; text-align: center;">
                        <i class="fas fa-comments" style="font-size: 2rem; color: #e7dba9; margin-bottom: 0.5rem;"></i>
                        <h3 style="color: #e7dba9; margin: 0.5rem 0;">Total Threads</h3>
                        <p style="font-size: 1.5rem; color: #fff; margin: 0;"><?php echo $total_threads; ?></p>
                    </div>
                    <div style="background: #181818; padding: 1.5rem; border-radius: 8px; text-align: center;">
                        <i class="fas fa-reply-all" style="font-size: 2rem; color: #e7dba9; margin-bottom: 0.5rem;"></i>
                        <h3 style="color: #e7dba9; margin: 0.5rem 0;">Total Comments</h3>
                        <p style="font-size: 1.5rem; color: #fff; margin: 0;"><?php echo $total_comments; ?></p>
                    </div>
                    <div style="background: #181818; padding: 1.5rem; border-radius: 8px; text-align: center;">
                        <i class="fas fa-clock" style="font-size: 2rem; color: #e7dba9; margin-bottom: 0.5rem;"></i>
                        <h3 style="color: #e7dba9; margin: 0.5rem 0;">Pending Songs</h3>
                        <p style="font-size: 1.5rem; color: #fff; margin: 0;"><?php echo $pending_songs; ?></p>
                    </div>
                </div>
                <div style="margin-top: 2rem;">
                    <h3 style="color: #e7dba9; margin-bottom: 1rem;">Time-based Statistics</h3>
                    <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                        <select id="time-period" style="background: #232323; color: #e7dba9; border: 1px solid #464646; padding: 8px; border-radius: 4px;">
                            <option value="day">Past Day</option>
                            <option value="week">Past Week</option>
                            <option value="month">Past Month</option>
                            <option value="year">Past Year</option>
                        </select>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                        <?php
                        function getTimeBasedStats($conn, $time_period) {
                            $time_conditions = [
                                'day' => 'DATE(dateadded) = CURDATE()',
                                'week' => 'dateadded >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)',
                                'month' => 'dateadded >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)',
                                'year' => 'dateadded >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)'
                            ];
                            
                            $condition = $time_conditions[$time_period];
                            $query = "SELECT COUNT(*) as count FROM notations WHERE $condition";
                            $result = $conn->query($query);
                            return $result ? $result->fetch_assoc()['count'] : 0;
                        }

                        $time_period = isset($_GET['time_period']) ? $_GET['time_period'] : 'day';
                        $notations_count = getTimeBasedStats($conn, $time_period);
                        ?>
                        <div style="background: #181818; padding: 1.5rem; border-radius: 8px; text-align: center;">
                            <i class="fas fa-file-alt" style="font-size: 2rem; color: #e7dba9; margin-bottom: 0.5rem;"></i>
                            <h3 style="color: #e7dba9; margin: 0.5rem 0;">Created Notations</h3>
                            <p style="font-size: 1.5rem; color: #fff; margin: 0;"><?php echo $notations_count; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="users" class="admin-section">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <h2 style="margin: 0;">Users</h2>
                <button id="toggle-users-btn" style="background: #232323; color: #e7dba9; border: 1px solid #464646; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; cursor: pointer; transition: background 0.3s;" title="Collapse/Expand Users">
                    <span id="toggle-users-arrow" style="display: inline-block; transition: transform 0.3s;">&#9660;</span>
                </button>
            </div>
            <div id="users-content">
            <div id="users-table-container">
            <table style="width:100%; border-collapse:collapse; background:#232323; color:#e7dba9;">
                <thead>
                    <tr style="background:#181818;">
                        <th style="padding:8px; border-bottom:1px solid #444;">Username</th>
                        <th style="padding:8px; border-bottom:1px solid #444;">Admin</th>
                        <th style="padding:8px; border-bottom:1px solid #444;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $users_result = $conn->query("SELECT userid, username, moderatorstatus FROM users ORDER BY userid DESC");
                if (!$users_result) {
                    echo '<tr><td colspan="5" style="color:#c0392b;">SQL Error: ' . htmlspecialchars($conn->error) . '</td></tr>';
                } else {
                while ($user = $users_result->fetch_assoc()):
                ?>
                    <tr>
                        <td style="padding:8px; border-bottom:1px solid #333;"> <?php echo htmlspecialchars($user['username']); ?> </td>
                        <td style="padding:8px; border-bottom:1px solid #333; text-align:center;">
                            <?php echo $user['moderatorstatus'] ? '<span style="color:#ffcc00;">&#9812; Admin</span>' : 'User'; ?>
                        </td>
                        <td style="padding:8px; border-bottom:1px solid #333; text-align:center;">
                            <?php if ($user['moderatorstatus']): ?>
                                <form class="user-action-form" method="post" style="display:inline;"><input type="hidden" name="userid" value="<?php echo $user['userid']; ?>"><button type="submit" name="revoke_admin" style="background:#e7dba9; color:#232323; border:none; padding:4px 10px; border-radius:3px; cursor:pointer;">Revoke Admin</button></form>
                            <?php else: ?>
                                <form class="user-action-form" method="post" style="display:inline;"><input type="hidden" name="userid" value="<?php echo $user['userid']; ?>"><button type="submit" name="grant_admin" style="background:#e7dba9; color:#232323; border:none; padding:4px 10px; border-radius:3px; cursor:pointer;">Grant Admin</button></form>
                            <?php endif; ?>
                            <form class="user-action-form" method="post" style="display:inline; margin-left:5px;"><input type="hidden" name="userid" value="<?php echo $user['userid']; ?>"><button type="submit" name="delete_user" style="background:#c0392b; color:#fff; border:none; padding:4px 10px; border-radius:3px; cursor:pointer;">Delete</button></form>
                        </td>
                    </tr>
                <?php endwhile; } ?>
                </tbody>
            </table>
            </div>
            </div>
        </div>
        <div id="song-approval" class="admin-section">
            <h2>Approve or Decline Song Requests</h2>
            <?php
            // Fetch pending songs
            $pending_songs = [];
            $pending_result = $conn->query("SELECT * FROM songs WHERE status = 'pending'");
            if ($pending_result && $pending_result->num_rows > 0) {
                while ($row = $pending_result->fetch_assoc()) {
                    $pending_songs[] = $row;
                }
            }
            // Handle approval/decline
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['song_action'], $_POST['songid'])) {
                $songid = intval($_POST['songid']);
                $action = $_POST['song_action'] === 'approve' ? 'approved' : 'rejected';
                $conn->query("UPDATE songs SET status='$action' WHERE songid=$songid");
                echo '<div style="color: #4caf50;">Song #' . $songid . ' has been ' . $action . '.</div>';
                // Refresh the list
                $pending_songs = [];
                $pending_result = $conn->query("SELECT * FROM songs WHERE status = 'pending'");
                if ($pending_result && $pending_result->num_rows > 0) {
                    while ($row = $pending_result->fetch_assoc()) {
                        $pending_songs[] = $row;
                    }
                }
            }
            ?>
            <?php if (empty($pending_songs)): ?>
                <p style="color: #888;">No pending song requests.</p>
            <?php else: ?>
                <table style="width:100%; background:#232323; color:#fff; border-collapse:collapse;">
                    <tr style="background:#181818;">
                        <th style="padding:8px; border:1px solid #444;">ID</th>
                        <th style="padding:8px; border:1px solid #444;">Title</th>
                        <th style="padding:8px; border:1px solid #444;">Performer</th>
                        <th style="padding:8px; border:1px solid #444;">Requested By (UserID)</th>
                        <th style="padding:8px; border:1px solid #444;">Actions</th>
                    </tr>
                    <?php foreach ($pending_songs as $song): ?>
                    <tr>
                        <td style="padding:8px; border:1px solid #444;">#<?php echo $song['songid']; ?></td>
                        <td style="padding:8px; border:1px solid #444;"><?php echo htmlspecialchars($song['title']); ?></td>
                        <td style="padding:8px; border:1px solid #444;"><?php echo htmlspecialchars($song['performer']); ?></td>
                        <td style="padding:8px; border:1px solid #444;"><?php echo $song['userid']; ?></td>
                        <td style="padding:8px; border:1px solid #444;">
                            <form method="POST" action="#song-approval" style="display:inline;">
                                <input type="hidden" name="songid" value="<?php echo $song['songid']; ?>">
                                <button type="submit" name="song_action" value="approve" style="background:#4caf50; color:#fff; border:none; border-radius:4px; padding:6px 14px; margin-right:6px; cursor:pointer;">Approve</button>
                                <button type="submit" name="song_action" value="decline" style="background:#ff6b6b; color:#fff; border:none; border-radius:4px; padding:6px 14px; cursor:pointer;">Decline</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Scroll to Top Button -->
<button id="scroll-to-top-btn" style="position: fixed; right: 32px; bottom: 32px; width: 64px; height: 64px; border-radius: 50%; background: #232323; color: #e7dba9; border: 2px solid #464646; display: flex; align-items: center; justify-content: center; font-size: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.2); cursor: pointer; z-index: 1000; transition: background 0.2s;" title="Scroll to Top">
    <span style="display: inline-block; transform: rotate(-90deg); position: relative; top: -3px; left: -1px;">&#9654;</span>
</button>
<script>
// Collapse/Expand Users Section
const toggleBtn = document.getElementById('toggle-users-btn');
const usersContent = document.getElementById('users-content');
const arrow = document.getElementById('toggle-users-arrow');
let usersCollapsed = false;
toggleBtn.addEventListener('click', function() {
    usersCollapsed = !usersCollapsed;
    usersContent.style.display = usersCollapsed ? 'none' : '';
    arrow.style.transform = usersCollapsed ? 'rotate(-90deg )' : 'rotate(0deg)';
});
// Scroll to Top Button
const scrollBtn = document.getElementById('scroll-to-top-btn');
scrollBtn.addEventListener('click', function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
// Offset scroll for anchor navigation (navbar height)
function offsetAnchorScroll() {
    const navbar = document.querySelector('.navbar-spacer');
    const offset = navbar ? navbar.offsetHeight : 60;
    if (window.location.hash) {
        const el = document.getElementById(window.location.hash.substring(1));
        if (el) {
            const y = el.getBoundingClientRect().top + window.pageYOffset - offset - 10;
            window.scrollTo({top: y, behavior: 'smooth'});
        }
    }
}
document.querySelectorAll('.admin-nav a').forEach(link => {
    link.addEventListener('click', function(e) {
        // Let the hash update, then scroll
        setTimeout(offsetAnchorScroll, 10);
    });
});
// On page load with hash
window.addEventListener('DOMContentLoaded', function() {
    if (window.location.hash) {
        setTimeout(offsetAnchorScroll, 10);
    }
    // Delete user confirmation
    document.querySelectorAll("form button[name='delete_user']").forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
});
// AJAX for user action forms
function attachUserActionAJAX() {
    document.querySelectorAll('.user-action-form').forEach(form => {
        // Track which button was clicked
        form.querySelectorAll('button[type=submit]').forEach(btn => {
            btn.addEventListener('click', function(event) {
                form._clickedButton = btn;
            });
        });
        form.onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            // Add the clicked button's name/value
            if (form._clickedButton && form._clickedButton.name) {
                formData.append(form._clickedButton.name, form._clickedButton.value || '1');
            }
            // Confirmation for delete
            if (form._clickedButton && form._clickedButton.name === 'delete_user') {
                if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                    return;
                }
            }
            fetch('admin_users_table.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(html => {
                document.getElementById('users-table-container').innerHTML = html;
                attachUserActionAJAX(); // re-attach to new forms
            });
        };
    });
}
attachUserActionAJAX();
// Toggle Stats Section
const toggleStatsBtn = document.getElementById('toggle-stats-btn');
const statsContent = document.getElementById('stats-content');
const statsArrow = document.getElementById('toggle-stats-arrow');
let statsCollapsed = false;
toggleStatsBtn.addEventListener('click', function() {
    statsCollapsed = !statsCollapsed;
    statsContent.style.display = statsCollapsed ? 'none' : '';
    statsArrow.style.transform = statsCollapsed ? 'rotate(-90deg)' : 'rotate(0deg)';
});
// Time Period Selection
document.getElementById('time-period').addEventListener('change', function() {
    const timePeriod = this.value;
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('time_period', timePeriod);
    window.location.href = currentUrl.toString();
});

// Set the selected time period in the dropdown
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const timePeriod = urlParams.get('time_period') || 'day';
    document.getElementById('time-period').value = timePeriod;
});
</script>
</body>
</html> 
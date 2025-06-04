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
        <yle="color:#e7dba9;">Admin Panel</h1>
        <div class="admin-nav">
            <a href="#users">Users</a>
            <a href="#audio">Audio</a>
            <a href="#songs">Songs</a>
            <a href="#threads">Threads</a>
            <a href="#comments">Thread Comments</a>
            <a href="#categories">Notation Categories</a>
            <a href="#settings">System Settings</a>
        </div>
        <div id="users" class="admin-section">
            <h2>Users</h2>
            <table style="width:100%; border-collapse:collapse; background:#232323; color:#e7dba9;">
                <thead>
                    <tr style="background:#181818;">
                        <th style="padding:8px; border-bottom:1px solid #444;">Username</th>
                        <th style="padding:8px; border-bottom:1px solid #444;">Email</th>
                        <th style="padding:8px; border-bottom:1px solid #444;">Admin</th>
                        <th style="padding:8px; border-bottom:1px solid #444;">Registered</th>
                        <th style="padding:8px; border-bottom:1px solid #444;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $users_result = $conn->query("SELECT userid, username, email, moderatorstatus, datecreated FROM users ORDER BY datecreated DESC");
                if (!$users_result) {
                    echo '<tr><td colspan="5" style="color:#c0392b;">SQL Error: ' . htmlspecialchars($conn->error) . '</td></tr>';
                } else {
                while ($user = $users_result->fetch_assoc()):
                ?>
                    <tr>
                        <td style="padding:8px; border-bottom:1px solid #333;"> <?php echo htmlspecialchars($user['username']); ?> </td>
                        <td style="padding:8px; border-bottom:1px solid #333;"> <?php echo htmlspecialchars($user['email']); ?> </td>
                        <td style="padding:8px; border-bottom:1px solid #333; text-align:center;">
                            <?php echo $user['moderatorstatus'] ? '<span style="color:#ffcc00;">&#9812; Admin</span>' : 'User'; ?>
                        </td>
                        <td style="padding:8px; border-bottom:1px solid #333;"> <?php echo date('Y-m-d', strtotime($user['datecreated'])); ?> </td>
                        <td style="padding:8px; border-bottom:1px solid #333;">
                            <?php if ($user['moderatorstatus']): ?>
                                <form method="post" style="display:inline;"><input type="hidden" name="userid" value="<?php echo $user['userid']; ?>"><button type="submit" name="revoke_admin" style="background:#e7dba9; color:#232323; border:none; padding:4px 10px; border-radius:3px; cursor:pointer;">Revoke Admin</button></form>
                            <?php else: ?>
                                <form method="post" style="display:inline;"><input type="hidden" name="userid" value="<?php echo $user['userid']; ?>"><button type="submit" name="grant_admin" style="background:#e7dba9; color:#232323; border:none; padding:4px 10px; border-radius:3px; cursor:pointer;">Grant Admin</button></form>
                            <?php endif; ?>
                            <form method="post" style="display:inline; margin-left:5px;"><input type="hidden" name="userid" value="<?php echo $user['userid']; ?>"><button type="submit" name="delete_user" style="background:#c0392b; color:#fff; border:none; padding:4px 10px; border-radius:3px; cursor:pointer;">Delete</button></form>
                        </td>
                    </tr>
                <?php endwhile; } ?>
                </tbody>
            </table>
        </div>
        <div id="audio" class="admin-section">
            <h2>Audio</h2>
            <ul>
                <li>Replace or delete any audio attached to a notation</li>
            </ul>
        </div>
        <div id="songs" class="admin-section">
            <h2>Songs</h2>
            <ul>
                <li>Manage song records or data</li>
            </ul>
        </div>
        <div id="threads" class="admin-section">
            <h2>Threads</h2>
            <ul>
                <li>Edit any user-created thread</li>
                <li>Delete threads that violate rules or contain unwanted content</li>
            </ul>
        </div>
        <div id="comments" class="admin-section">
            <h2>Thread Comments</h2>
            <ul>
                <li>Edit any comment</li>
                <li>Delete comments that are offensive, spam, or violate site policy</li>
            </ul>
        </div>
        <div id="categories" class="admin-section">
            <h2>Notation Categories</h2>
            <ul>
                <li>Manage (create, edit, delete) categories for filtering/searching notations (e.g., "classical", "pop", "jazz", etc.)</li>
            </ul>
        </div>
        <div id="settings" class="admin-section">
            <h2>System Settings</h2>
            <ul>
                <li>Configure global parameters (e.g., max audio/notation size, PDF generation standards, access restrictions)</li>
            </ul>
        </div>
    </div>
</div>
</body>
</html> 
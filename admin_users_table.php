<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

// Only allow admins
if (!isLoggedIn() || !isset($_SESSION['userid'])) {
    http_response_code(403);
    exit('Forbidden');
}
$user_id = $_SESSION['userid'];
$admin_check = $conn->query("SELECT moderatorstatus FROM users WHERE userid = '$user_id'");
$is_admin = $admin_check && $admin_check->fetch_assoc()['moderatorstatus'] == 1;
if (!$is_admin) {
    http_response_code(403);
    exit('Forbidden');
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userid'])) {
    $target_id = intval($_POST['userid']);
    if (isset($_POST['grant_admin'])) {
        $conn->query("UPDATE users SET moderatorstatus = 1 WHERE userid = $target_id");
    } elseif (isset($_POST['revoke_admin'])) {
        $conn->query("UPDATE users SET moderatorstatus = 0 WHERE userid = $target_id");
    } elseif (isset($_POST['delete_user'])) {
        // Delete all comments by this user
        $delete_comments = $conn->query("DELETE FROM threadcomments WHERE userid = $target_id");
        if (!$delete_comments) {
            echo '<tr><td colspan="5" style="color:#c0392b;">Error deleting comments: ' . htmlspecialchars($conn->error) . '</td></tr>';
        }
        // Delete all threads by this user
        $delete_threads = $conn->query("DELETE FROM threads WHERE createdby = $target_id");
        if (!$delete_threads) {
            echo '<tr><td colspan="5" style="color:#c0392b;">Error deleting threads: ' . htmlspecialchars($conn->error) . '</td></tr>';
        }
        // Set userid to NULL for all notations by this user
        $null_notations = $conn->query("UPDATE notations SET userid = NULL WHERE userid = $target_id");
        if (!$null_notations) {
            echo '<tr><td colspan="5" style="color:#c0392b;">Error updating notations: ' . htmlspecialchars($conn->error) . '</td></tr>';
        }
        // Now delete the user
        $delete_user = $conn->query("DELETE FROM users WHERE userid = $target_id");
        if (!$delete_user) {
            echo '<tr><td colspan="5" style="color:#c0392b;">Error deleting user: ' . htmlspecialchars($conn->error) . '</td></tr>';
        }
    }
}

$users_result = $conn->query("SELECT userid, username, moderatorstatus FROM users ORDER BY userid DESC");
?>
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
<?php
// After table, show any SQL error
if ($conn->error) {
    echo '<div style="color:#c0392b;">SQL Error: ' . htmlspecialchars($conn->error) . '</div>';
} 
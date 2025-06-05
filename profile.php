<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$user_id = $_SESSION['userid'];

// Fetch the count of threads and notations
$thread_count_sql = "SELECT COUNT(*) as thread_count FROM threads WHERE createdby = '$user_id'";
$thread_count_result = $conn->query($thread_count_sql);
$thread_count = $thread_count_result->fetch_assoc()['thread_count'];

$notation_count_sql = "SELECT COUNT(*) as notation_count FROM notations WHERE userid = '$user_id'";
$notation_count_result = $conn->query($notation_count_sql);
$notation_count = $notation_count_result->fetch_assoc()['notation_count'];

// Fetch moderatorstatus for the logged-in user
$user_sql = "SELECT moderatorstatus FROM users WHERE userid = '$user_id'";
$user_result = $conn->query($user_sql);
$user_row = $user_result->fetch_assoc();
$is_admin = isset($user_row['moderatorstatus']) && $user_row['moderatorstatus'] == 1;
$admin_symbol = $is_admin ? ' <span title="Admin" style="color:#ffcc00;">&#9812;</span>' : '';

// Handle change username
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_username'])) {
    $new_username = trim($_POST['new_username']);
    
    // Validation - same as registration plus additional rules
    if (empty($new_username)) {
        $error_message = 'Username cannot be empty.';
    } elseif (strlen($new_username) < 3) {
        $error_message = 'Username must be at least 3 characters long.';
    } elseif (strlen($new_username) > 30) {
        $error_message = 'Username must be no more than 30 characters long.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $new_username)) {
        $error_message = 'Username can only contain letters, numbers, and underscores.';
    } elseif ($new_username === $username) {
        $error_message = 'This is already your current username.';
    } else {
        // Check if username already exists (excluding current user)
        $check_sql = "SELECT userid FROM users WHERE username = ? AND userid != ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("si", $new_username, $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error_message = 'Username already exists. Please choose a different username.';
        } else {
            // Update username using prepared statement
            $change_sql = "UPDATE users SET username = ? WHERE userid = ?";
            $change_stmt = $conn->prepare($change_sql);
            $change_stmt->bind_param("si", $new_username, $user_id);
            
            if ($change_stmt->execute()) {
                $_SESSION['username'] = $new_username;
                $username = $new_username;
                $success_message = 'Username successfully changed.';
            } else {
                $error_message = 'Error updating username. Please try again.';
            }
        }
    }
}

// Handle change password
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password: only letters, numbers, and symbols
    if (!preg_match('/^[a-zA-Z0-9!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?]*$/', $new_password)) {
        $error_message = 'Password contains invalid characters.';
    } elseif (strlen($new_password) < 8) {
        $error_message = 'Password must be at least 8 characters long.';
    } elseif ($new_password !== $confirm_password) {
        $error_message = 'Passwords do not match.';
    } else {
        // Check if the old password is correct
        $password_check_sql = "SELECT password FROM users WHERE userid = '$user_id'";
        $password_check_result = $conn->query($password_check_sql);
        $user = $password_check_result->fetch_assoc();

        if (password_verify($old_password, $user['password'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $change_password_sql = "UPDATE users SET password = '$hashed_password' WHERE userid = '$user_id'";
            if ($conn->query($change_password_sql) === TRUE) {
                $success_message = 'Password successfully changed.';
            } else {
                $error_message = 'Error: ' . $conn->error;
            }
        } else {
            $error_message = 'Old password is incorrect.';
        }
    }
}

// Handle delete profile
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_profile'])) {
    $delete_sql = "DELETE FROM users WHERE userid = '$user_id'";
    if ($conn->query($delete_sql) === TRUE) {
        session_destroy();
        header('Location: index.html');
        exit;
    } else {
        $error_message = 'Error: ' . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - NoteTone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="navbar-spacer"></div>
    <div class="wrapper-profile">
        <h2>Your Profile</h2>
         <div class="profile-box">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($username) . $admin_symbol; ?></p>
            <p><strong>Threads Created:</strong> <?php echo $thread_count; ?></p>
            <p><strong>Notations Created:</strong> <?php echo $notation_count; ?></p>
            <?php if ($is_admin): ?>
            <p><a href="admin_panel.php" class="btn btn-primary" style="margin-top:10px; display:inline-block;">Admin Panel</a></p>
            <?php endif; ?>
        </div>

        <?php if (isset($success_message)): ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
       
        <form method="POST" action="">
            <div class="input-box">
                <input type="text" name="new_username" placeholder="New Username">
            </div>
            <button class="btn btn-primary" type="submit" name="change_username">Change Username</button>
        </form>
        <form method="POST" action="">
            <div class="input-box">
                <input type="password" name="old_password" placeholder="Old Password">
            </div>
            <div class="input-box">
                <input type="password" name="new_password" placeholder="New Password">
            </div>
            <div class="input-box">
                <input type="password" name="confirm_password" placeholder="Confirm New Password">
            </div>
            <button class="btn btn-primary" type="submit" name="change_password">Change Password</button>
        </form>
        <form method="POST" action="">
            <button class="btn-delete-profile" type="submit" name="delete_profile">Delete Profile</button>
        </form>
    </div>
</body>
</html>

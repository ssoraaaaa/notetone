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

// Handle change username
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_username'])) {
    $new_username = $_POST['new_username'];
    if (!empty($new_username)) {
        $change_sql = "UPDATE users SET username = '$new_username' WHERE userid = '$user_id'";
        if ($conn->query($change_sql) === TRUE) {
            $_SESSION['username'] = $new_username;
            $username = $new_username;
            $success_message = 'Username successfully changed.';
        } else {
            $error_message = 'Error: ' . $conn->error;
        }
    } else {
        $error_message = 'New username cannot be empty.';
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
        <div class="wrapper-profile">
            <h2>Your Profile</h2>
             <div class="profile-box">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
                <p><strong>Threads Created:</strong> <?php echo $thread_count; ?></p>
                <p><strong>Notations Created:</strong> <?php echo $notation_count; ?></p>
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
                <button class="btn-change-username" type="submit" name="change_username">Change Username</button>
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
                <button class="btn-change-password" type="submit" name="change_password">Change Password</button>
            </form>
            <form method="POST" action="">
                <button class="btn-delete-profile" type="submit" name="delete_profile">Delete Profile</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log the start of the request
error_log("Delete account verification started");

if (!isLoggedIn()) {
    error_log("User not logged in");
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$password = $_POST['password'] ?? '';

// Get user ID from session - using the correct session variable name
$user_id = $_SESSION['userid'] ?? null;

error_log("Session contents: " . print_r($_SESSION, true));
error_log("User ID from session: " . ($user_id ?? 'null'));

if (empty($password)) {
    error_log("Empty password provided");
    echo json_encode(['success' => false, 'message' => 'Password is required']);
    exit;
}

if (empty($user_id)) {
    error_log("No user ID in session");
    echo json_encode(['success' => false, 'message' => 'Session error - please log in again']);
    exit;
}

try {
    // Check if the password is correct
    $password_check_sql = "SELECT password FROM users WHERE userid = ?";
    $stmt = $conn->prepare($password_check_sql);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        echo json_encode(['success' => false, 'message' => 'Database error occurred']);
        exit;
    }

    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Database error occurred']);
        exit;
    }

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        error_log("User not found in database: " . $user_id);
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }

    error_log("Password verification attempt for user: " . $user_id);
    if (password_verify($password, $user['password'])) {
        error_log("Password verified successfully for user: " . $user_id);
        
        // Password is correct, delete the account
        $delete_sql = "DELETE FROM users WHERE userid = ?";
        $stmt = $conn->prepare($delete_sql);
        if (!$stmt) {
            error_log("Delete prepare failed: " . $conn->error);
            echo json_encode(['success' => false, 'message' => 'Database error occurred']);
            exit;
        }

        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) {
            error_log("Delete execute failed: " . $stmt->error);
            echo json_encode(['success' => false, 'message' => 'Error deleting account. Please try again.']);
            exit;
        }

        error_log("Account deleted successfully for user: " . $user_id);
        session_destroy();
        echo json_encode(['success' => true, 'redirect' => 'index.php?deleted=true']);
    } else {
        error_log("Incorrect password for user: " . $user_id);
        echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
    }
} catch (Exception $e) {
    error_log("Exception occurred: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An unexpected error occurred']);
}
?> 
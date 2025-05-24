<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$notation_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch the notation details
$notation_sql = "SELECT n.*, s.title AS song_title, i.name AS instrument_name, u.username AS user_name 
                 FROM notations n 
                 LEFT JOIN songs s ON n.songid = s.songid 
                 LEFT JOIN instruments i ON n.instrumentid = i.instrumentid 
                 LEFT JOIN users u ON n.userid = u.userid 
                 WHERE n.notationid = '$notation_id'";
$notation_result = $conn->query($notation_sql);
$notation = $notation_result->fetch_assoc();

if (!$notation) {
    echo "Notation not found.";
    exit;
}

// Handle deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    if ($notation['userid'] == $user_id) {
        $delete_sql = "DELETE FROM notations WHERE notationid = '$notation_id'";
        if ($conn->query($delete_sql) === TRUE) {
            header('Location: notations.php');
            exit;
        } else {
            $error_message = 'Error: ' . $conn->error;
        }
    } else {
        $error_message = 'You do not have permission to delete this notation.';
    }
}

// Set page title and additional resources
$page_title = htmlspecialchars($notation['title']) . ' - NoteTone';
$additional_css = ['https://cdn.jsdelivr.net/npm/opensheetmusicdisplay@1.7.6/build/opensheetmusicdisplay.min.css'];
$additional_js = [
    'https://cdn.jsdelivr.net/npm/opensheetmusicdisplay@1.7.6/build/opensheetmusicdisplay.min.js',
    'assets/js/notation.js'
];

include('includes/header.php');
?>

<div class="wrapper-notation">
    <h2><?php echo htmlspecialchars($notation['title']); ?></h2>
    <?php if (isset($error_message)): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <div class="notation-box">
        <p><strong>Song:</strong> <?php echo htmlspecialchars($notation['song_title']); ?></p>
        <p><strong>Instrument:</strong> <?php echo htmlspecialchars($notation['instrument_name']); ?></p>
        <p><strong>Date Added:</strong> <?php echo htmlspecialchars($notation['dateadded']); ?></p>
        <p><strong>Created by:</strong> <?php echo $notation['user_name'] ? htmlspecialchars($notation['user_name']) : '<em>deleted user</em>'; ?></p>
    </div>
    <div class="notation-box">
        <div id="osmd-container"></div>
        <pre id="musicxml-source" style="display:none;"><?php echo htmlspecialchars($notation['content']); ?></pre>
    </div>
    <?php if ($notation['userid'] == $user_id): ?>
    <form method="POST" action="">
        <button class="btn-delete-notation" type="submit" name="delete">Delete Notation</button>
    </form>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>

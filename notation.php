<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$notation_id = $_GET['id'];
$user_id = $_SESSION['userid'];

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

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <ul class="header">
        <a href="dashboard.php"><img src="logo-gray.png" class="header_logo" alt="Logo"></a>
        <li class="li_header"><a class="a_header" href="dashboard.php">Dashboard</a></li>
        <li class="li_header"><a class="a_header" href="threads.php">Threads</a></li>
        <li class="li_header"><a class="a_header" href="notations.php">Notations</a></li>
        <li class="li_header"><a class="a_header" href="mythreads.php">My Threads</a></li>
        <li class="li_header"><a class="a_header" href="mynotations.php">My Notations</a></li>
        <li class="li_header"><a class="a_header" href="profile.php">Profile</a></li>
        <li class="li_header"><a class="a_header" href="logout.php">Logout</a></li>
    </ul>

<div class="wrapper-notation" style="width: 80%; max-width: 900px; margin: 40px auto; background: #232323; border-radius: 8px; padding: 32px 24px; box-shadow: 0 2px 16px #0002;">
    <h2 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #fff; margin-bottom: 20px; font-size: 2rem; text-align: center;">
        <?php echo htmlspecialchars($notation['title']); ?>
    </h2>
    <?php if (isset($error_message)): ?>
        <p class="error" style="color: #ff6b6b; text-align: center; margin-bottom: 20px; font-size: 1.1rem;"> <?php echo $error_message; ?> </p>
    <?php endif; ?>
    <div class="notation-box" style="background: #2a2a2a; border: 1px solid #464646; border-radius: 4px; padding: 18px 20px; margin-bottom: 24px;">
        <p><strong>Song:</strong> <?php echo htmlspecialchars($notation['song_title']); ?></p>
        <p><strong>Instrument:</strong> <?php echo htmlspecialchars($notation['instrument_name']); ?></p>
        <p><strong>Date Added:</strong> <?php echo htmlspecialchars($notation['dateadded']); ?></p>
        <p><strong>Created by:</strong> <?php echo $notation['user_name'] ? htmlspecialchars($notation['user_name']) : '<em>deleted user</em>'; ?></p>
    </div>
    <div class="notation-box" style="background: #1a1a1a; border: 1px solid #464646; border-radius: 4px; padding: 18px 20px; margin-bottom: 24px;">
        <div id="vf-container"></div>
        <div id="vf-tab-container"></div>
    </div>
    <?php if ($notation['userid'] == $user_id): ?>
    <form method="POST" action="">
        <button class="btn-delete-notation" type="submit" name="delete" style="background: #ff6b6b; color: #fff; border: none; border-radius: 4px; padding: 10px 22px; font-size: 1rem; cursor: pointer;">Delete Notation</button>
    </form>
    <?php endif; ?>
</div>
<?php include('includes/footer.php'); ?>
<script src="https://unpkg.com/vexflow/releases/vexflow-min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vexflow@4.2.2/build/cjs/vexflow.js"></script>
<script>
    // Get the notation content from PHP
    const notationContent = <?php echo json_encode($notation['content']); ?>;
    const VF = Vex.Flow;
    const div = document.getElementById('vf-container');
    const renderer = new VF.Renderer(div, VF.Renderer.Backends.SVG);
    renderer.resize(500, 180);
    const context = renderer.getContext();
    context.setFont('Arial', 10, '').setBackgroundFillStyle('#1a1a1a');
    const stave = new VF.Stave(10, 40, 400);
    stave.addClef('treble').addTimeSignature('4/4');
    stave.setContext(context).draw();
    try {
        // Use EasyScore for simple input
        const factory = new VF.Factory({renderer: {elementId: 'vf-container', width: 500, height: 180}});
        const score = factory.EasyScore();
        const system = factory.System();
        system.addStave({ voices: [score.voice(score.notes(notationContent))] }).addClef('treble').addTimeSignature('4/4');
        factory.draw();
    } catch (e) {
        div.innerHTML = '<span style="color: #ff6b6b">Could not render notation: ' + e.message + '</span>';
    }

    const tabNotesData = <?php echo json_encode(json_decode($notation['content'])); ?>;
    function renderTab(notes) {
        const VF = Vex.Flow;
        const div = document.getElementById('vf-tab-container');
        div.innerHTML = '';
        if (!Array.isArray(notes) || notes.length === 0) {
            div.innerHTML = '<div style="color:#888;text-align:center;padding:20px;">No tab notes to display.</div>';
            return;
        }
        const renderer = new VF.Renderer(div, VF.Renderer.Backends.SVG);
        renderer.resize(500, 180);
        const context = renderer.getContext();
        const tabStave = new VF.TabStave(10, 40, 400);
        tabStave.addClef('tab').setContext(context).draw();
        const vfNotes = notes.map(note => new VF.TabNote({positions: [{str: note.str, fret: note.fret}], duration: note.duration}));
        const voice = new VF.Voice().setStrict(false);
        voice.addTickables(vfNotes);
        new VF.Formatter().joinVoices([voice]).format([voice], 400);
        voice.draw(context, tabStave);
    }
    renderTab(tabNotesData);
</script>

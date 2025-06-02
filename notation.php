<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

$notation_id = $_GET['id'];

// Fetch the notation details
$notation_sql = "SELECT n.*, n.userid, s.title AS song_title, i.name AS instrument_name, u.username AS user_name 
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

// Determine entry source
$from = isset($_GET['from']) ? $_GET['from'] : '';
$back_url = isset($_GET['back']) ? $_GET['back'] : ($from === 'mynotations' ? 'mynotations.php' : ($from === 'notations' ? 'notations.php' : (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'notations.php')));

// Handle deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
    if (isset($notation['userid']) && $notation['userid'] == $_SESSION['userid']) {
        $delete_sql = "DELETE FROM notations WHERE notationid = '$notation_id'";
        if ($conn->query($delete_sql) === TRUE) {
            if ($from === 'mynotations') {
                header('Location: mynotations.php');
            } else {
            header('Location: notations.php');
            }
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

// After $back_url is set, add logic to prevent edit.php as a back target
if (strpos($back_url, 'edit.php') !== false) {
    // If user came from mynotations, go there, else fallback to notations.php
    if (isset($_GET['back']) && strpos($_GET['back'], 'mynotations.php') !== false) {
        $back_url = 'mynotations.php';
    } else {
        $back_url = 'notations.php';
    }
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

<div class="wrapper-notation" style="width: 80%; max-width: 1200px; margin: 40px auto; background: #232323; border-radius: 8px; padding: 32px 24px; box-shadow: 0 2px 16px #0002;">
    <h2 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #fff; margin-bottom: 20px; font-size: 2rem; text-align: center;">
        <?php echo htmlspecialchars($notation['title']); ?>
    </h2>
    <?php if (isset($error_message)): ?>
        <p class="error" style="color: #ff6b6b; text-align: center; margin-bottom: 20px; font-size: 1.1rem;"> <?php echo $error_message; ?> </p>
    <?php endif; ?>
    <div class="tab-description" style="background: #2a2a2a; border: 1px solid #464646; border-radius: 4px; padding: 18px 20px; margin-bottom: 24px;">
        <p><strong>Song:</strong> <?php echo htmlspecialchars($notation['song_title']); ?></p>
        <p><strong>Instrument:</strong> <?php echo htmlspecialchars($notation['instrument_name']); ?></p>
        <p><strong>Date Added:</strong> <?php echo htmlspecialchars($notation['dateadded']); ?></p>
        <p><strong>Created by:</strong> <?php echo $notation['user_name'] ? htmlspecialchars($notation['user_name']) : '<em>deleted user</em>'; ?></p>
    </div>
    <div class="notation-box" style="background: #1a1a1a; border: 1px solid #464646; border-radius: 4px; padding: 18px 20px; margin-bottom: 24px;">
        <div id="vf-container"></div>
    </div>
    <?php if (isLoggedIn() && isset($notation['userid']) && $notation['userid'] == $_SESSION['userid'] && $from === 'mynotations'): ?>
    <form method="POST" action="" style="display:inline-block; margin-right: 10px;">
        <button class="btn-delete-notation" type="submit" name="delete" style="background: #ff6b6b; color: #fff; border: none; border-radius: 4px; padding: 10px 22px; font-size: 1rem; cursor: pointer;">Delete Notation</button>
    </form>
    <a href="edit.php?id=<?php echo $notation['notationid']; ?>&from=mynotations&back=<?php echo urlencode($back_url); ?>" class="btn btn-primary" style="text-decoration: none; padding: 10px 22px; font-size: 1rem; border-radius: 4px; background: #4faaff; color: #fff; border: none; margin-left: 10px;">Edit</a>
    <?php endif; ?>
    <a href="<?php echo htmlspecialchars($back_url); ?>" class="btn btn-primary" style="text-decoration: none; margin-left: 10px;">&larr; Back</a>
</div>
<?php include('includes/footer.php'); ?>
<script src="https://unpkg.com/vexflow/releases/vexflow-min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vexflow@4.2.2/build/cjs/vexflow.js"></script>
<script>
    // Get the notation content from PHP
    const notationContent = <?php echo json_encode($notation['content']); ?>;
    const VF = Vex.Flow;

    // Function to check if content is tab JSON
    function isTabNotation(content) {
        try {
            const parsed = JSON.parse(content);
            return Array.isArray(parsed) && parsed.length > 0 && 
                   (parsed[0].hasOwnProperty('str') || 
                    (Array.isArray(parsed[0].positions) && parsed[0].positions.length > 0));
        } catch (e) {
            return false;
        }
    }

    // Function to render tab notation
    function renderTabNotation(notes) {
        const div = document.getElementById('vf-container');
        div.innerHTML = '';
        const containerWidth = div.offsetWidth > 0 ? div.offsetWidth : 700;
        // Group notes into measures (assuming 4/4 time signature)
        let currentMeasure = [];
        let currentMeasureBeats = 0;
        const measures = [];
        const beats = 4; // Default to 4/4

        notes.forEach(note => {
            const noteBeats = getDurationInBeats(note.duration);
            if (currentMeasureBeats + noteBeats > beats) {
                if (currentMeasure.length > 0) {
                    measures.push(currentMeasure);
                }
                currentMeasure = [note];
                currentMeasureBeats = noteBeats;
            } else {
                currentMeasure.push(note);
                currentMeasureBeats += noteBeats;
            }
        });
        if (currentMeasure.length > 0) {
            measures.push(currentMeasure);
        }

        // Responsive measure width (copied from add_notation.php)
        const baseWidth = 60;
        const widthPerNote = 40;
        const minWidth = 100;
        const maxWidth = 260;
        const sidePadding = 10;
        const minSpacingWidth = 120;
        const extraDigitWidth = 8;

        // Calculate stave widths
        const staveWidths = measures.map(measure => {
            let noteCount = measure.length;
            let maxFretLen = 1;
            measure.forEach(note => {
                let positions = Array.isArray(note.positions) ? note.positions : [{str: note.str, fret: note.fret}];
                positions.forEach(pos => {
                    maxFretLen = Math.max(maxFretLen, String(pos.fret).length);
                });
            });
            const extraWidth = (maxFretLen - 1) * extraDigitWidth;
            const contentWidth = baseWidth + noteCount * widthPerNote + sidePadding * 2 + extraWidth;
            return Math.max(minWidth, Math.min(maxWidth, Math.max(minSpacingWidth, contentWidth)));
        });

        // Calculate how many measures fit per line
        let lines = [[]];
        let currentLineWidth = 10; // initial x offset
        let currentLine = 0;
        for (let i = 0; i < measures.length; i++) {
            if (currentLineWidth + staveWidths[i] > containerWidth && lines[currentLine].length > 0) {
                // Start new line
                lines.push([]);
                currentLine++;
                currentLineWidth = 10 + staveWidths[i];
                lines[currentLine].push(i);
            } else {
                lines[currentLine].push(i);
                currentLineWidth += staveWidths[i];
            }
        }

        // Calculate required SVG height
        const lineHeight = 200;
        const svgHeight = lines.length * lineHeight + 60;

        // Create renderer with dynamic width/height
        const renderer = new VF.Renderer(div, VF.Renderer.Backends.SVG);
        renderer.resize(containerWidth, svgHeight);
        const context = renderer.getContext();
        context.setFont('Arial', 18, '').setFillStyle('#fff');

        // Draw all measures
        let y = 40;
        lines.forEach(line => {
            let x = 10;
            line.forEach(idx => {
                const measure = measures[idx];
                const staveWidth = staveWidths[idx];
                const stave = new VF.TabStave(x, y, staveWidth);
                if (idx === 0) {
                    stave.addClef('tab');
                }
                stave.setContext(context).draw();
                const vfNotes = measure.map(note => {
                    let positions = Array.isArray(note.positions) ? note.positions : [{str: note.str, fret: note.fret}];
                    // Invert string numbers for VexFlow: 1 (high e) should be bottom, 6 (low E) should be top
                    positions = positions.map(pos => ({
                        str: 7 - pos.str, // invert string number
                        fret: pos.fret
                    }));
                    let vfNote = new VF.TabNote({
                        positions: positions,
                        duration: note.duration.replace('.', '')
                    }).setStyle({ fillStyle: '#fff', strokeStyle: '#fff' });
                    if (note.duration.endsWith('.')) {
                        vfNote.addDotToAll();
                    }
                    return vfNote;
                });
                const voice = new VF.Voice().setStrict(false);
                voice.addTickables(vfNotes);
                new VF.Formatter().joinVoices([voice]).format([voice], staveWidth - 20);
                voice.draw(context, stave);
                x += staveWidth;
            });
            y += lineHeight;
        });

        // Remove rectangles from tab numbers
        const svg = div.querySelector('svg');
        if (svg) {
            const tabNoteGroups = svg.querySelectorAll('g.vf-tabnote');
            tabNoteGroups.forEach(group => {
                const rects = group.querySelectorAll('rect');
                rects.forEach(rect => rect.remove());
            });
        }
    }

    // Function to get duration in beats
    function getDurationInBeats(duration) {
        const durationMap = {
            'w': 4,    // whole note
            'h': 2,    // half note
            'q': 1,    // quarter note
            '8': 0.5,  // eighth note
            '16': 0.25 // sixteenth note
        };
        return durationMap[duration] || 1;
    }

    // Initialize the notation display
    if (isTabNotation(notationContent)) {
        renderTabNotation(JSON.parse(notationContent));
    } else {
        // Display as plain text
        const div = document.getElementById('vf-container');
        div.innerHTML = `<pre style="color: #fff; font-family: monospace; white-space: pre-wrap;">${notationContent}</pre>`;
    }
</script>
</body>
</html>

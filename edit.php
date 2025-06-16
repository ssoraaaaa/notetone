<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Fetch songs
$song_sql = "SELECT * FROM songs WHERE status = 'approved'";
$song_result = $conn->query($song_sql);
$songs = [];
if ($song_result->num_rows > 0) {
    while ($row = $song_result->fetch_assoc()) {
        $songs[] = $row;
    }
} 

// Determine if we are editing or adding
$edit_mode = false;
$notation = null;
$notation_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$back_url = isset($_GET['back']) ? $_GET['back'] : (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null);
if ($notation_id) {
    $notation_sql = "SELECT * FROM notations WHERE notationid = ?";
    $stmt = $conn->prepare($notation_sql);
    $stmt->bind_param("i", $notation_id);
    $stmt->execute();
    $notation_result = $stmt->get_result();
    $notation = $notation_result->fetch_assoc();
    if ($notation && $notation['userid'] == $_SESSION['userid']) {
        $edit_mode = true;
    } else {
        $notation = null;
    }
}

// Fetch songs
$song_sql = "SELECT * FROM songs WHERE status = 'approved'";
$song_result = $conn->query($song_sql);
$songs = [];
if ($song_result->num_rows > 0) {
    while ($row = $song_result->fetch_assoc()) {
        $songs[] = $row;
    }
}

// Fetch instruments
$instrument_sql = "SELECT * FROM instruments";
$instrument_result = $conn->query($instrument_sql);
$instruments = [];
if ($instrument_result->num_rows > 0) {
    while ($row = $instrument_result->fetch_assoc()) {
        $instruments[] = $row;
    }
}

$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    // Normalize line endings to \n
    $content = str_replace(["\r\n", "\r"], "\n", $content);
    // Replace literal '\n' with real newlines
    $content = str_replace('\\n', "\n", $content);
    $songid = $_POST['songid'];
    $instrumentid = $_POST['instrumentid'];
    $userid = $_SESSION['userid'];
    $dateadded = date('Y-m-d');

    // Check if content is JSON (tab notation)
    $is_tab = false;
    try {
        json_decode($content);
        $is_tab = (json_last_error() == JSON_ERROR_NONE);
    } catch (Exception $e) {
        $is_tab = false;
    }
    if (!$is_tab) {
        $content = $conn->real_escape_string($content);
    }

    if ($edit_mode && $notation) {
        // Update
        $sql = "UPDATE notations SET title=?, content=?, songid=?, instrumentid=? WHERE notationid=? AND userid=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiiii", $title, $content, $songid, $instrumentid, $notation_id, $userid);
        $success = $stmt->execute();
        if ($success) {
            if ($is_ajax) {
                echo json_encode(['success' => true, 'edit' => true, 'id' => $notation_id, 'back' => $back_url ?? '']);
                exit;
            }
            // Always redirect to interactable notation.php
            header('Location: notation.php?id=' . $notation_id . '&from=mynotations&back=mynotations.php');
            exit;
        } else {
            if ($is_ajax) {
                echo json_encode(['success' => false, 'message' => $conn->error]);
                exit;
            }
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // Add
        $sql = "INSERT INTO notations (title, dateadded, content, songid, instrumentid, userid) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssii", $title, $dateadded, $content, $songid, $instrumentid, $userid);
        $success = $stmt->execute();
        if ($success) {
            $new_id = $conn->insert_id;
            if ($is_ajax) {
                echo json_encode(['success' => true, 'edit' => false, 'id' => $new_id, 'back' => $back_url ?? '']);
                exit;
            }
            // Always redirect to interactable notation.php
            header('Location: notation.php?id=' . $new_id . '&from=mynotations&back=mynotations.php');
            exit;
        } else {
            if ($is_ajax) {
                echo json_encode(['success' => false, 'message' => $conn->error]);
                exit;
            }
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Handle delete notation
if (
    $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_notation']) && $edit_mode && $notation
) {
    $delete_sql = "DELETE FROM notations WHERE notationid = ? AND userid = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("ii", $notation_id, $_SESSION['userid']);
    if ($stmt->execute()) {
        header('Location: mynotations.php');
        exit;
    } else {
        $error_message = 'Error deleting notation: ' . $conn->error;
    }
}

$page_title = $edit_mode ? 'Edit Notation - NoteTone' : 'Create Notation - NoteTone';
$button_text = $edit_mode ? 'Save Changes' : 'Create Notation';
$header_text = $edit_mode ? 'Edit Notation' : 'Create Notation';

// Prefill values for edit mode
$prefill = [
    'title' => $edit_mode && $notation ? htmlspecialchars($notation['title']) : '',
    'songid' => $edit_mode && $notation ? $notation['songid'] : '',
    'instrumentid' => $edit_mode && $notation ? $notation['instrumentid'] : '',
    'content' => $edit_mode && $notation ? $notation['content'] : '',
];
$prefill_js = json_encode([
    'title' => $prefill['title'],
    'songid' => $prefill['songid'],
    'instrumentid' => $prefill['instrumentid'],
    'content' => $prefill['content'],
]);
$prefill_mode = $edit_mode ? 'text' : 'tab';
if ($edit_mode && $notation) {
    // Try to detect if content is tab JSON
    $c = $notation['content'];
    $is_tab = false;
    try {
        $parsed = json_decode($c, true);
        $is_tab = is_array($parsed);
    } catch (Exception $e) {
        $is_tab = false;
    }
    $prefill_mode = $is_tab ? 'tab' : 'text';
} else {
    $prefill_mode = 'text'; // Show text editor by default for new notation
}

// Set the back link for the form
if ($edit_mode && $notation) {
    $from = isset($_GET['from']) ? $_GET['from'] : '';
    // Always set backLink to the interactable notation page
    $backLink = 'notation.php?id=' . $notation_id . '&from=mynotations&back=mynotations.php';
} else {
    $from = isset($_GET['from']) ? $_GET['from'] : '';
    $backLink = ($from === 'dashboard') ? 'dashboard.php' : 'mynotations.php';
}

// Use heredoc for real newlines in the tab preset
$tab_preset = <<<EOT
e---------------------------------------------------------------------------------------------------------------------------------------------------------------------
B---------------------------------------------------------------------------------------------------------------------------------------------------------------------
G---------------------------------------------------------------------------------------------------------------------------------------------------------------------
D---------------------------------------------------------------------------------------------------------------------------------------------------------------------
A---------------------------------------------------------------------------------------------------------------------------------------------------------------------
E---------------------------------------------------------------------------------------------------------------------------------------------------------------------
EOT;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="style.css">
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="navbar-spacer"></div>
    <div class="wrapper" style="width: 100%; max-width: 1300px; margin: 0 auto; overflow-x: auto;">
        <h2 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;"><?php echo $header_text; ?></h2>
        <form id="notation-form" method="POST" action="">
            <?php if ($back_url): ?>
                <input type="hidden" name="back" value="<?php echo htmlspecialchars($back_url); ?>">
            <?php endif; ?>
            <div class="form-group" style="width: 100%;">
                <input type="text" name="title" id="notation-title" class="form-control" placeholder="Title" required style="background: #2a2a2a; color: #fff; border: 1px solid #464646; margin-bottom: 20px; width: 100%; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; height: 45px;" value="<?php echo $prefill['title']; ?>">
            </div>
            <div class="form-group" style="width: 100%; margin-bottom: 20px;">
                <select name="songid" id="notation-songid" required style="width: 100%; height: 45px; background: #2a2a2a; color: #fff; border: 1px solid #464646; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 0 10px;">
                    <option value="">Select Song</option>
                    <?php foreach ($songs as $song): ?>
                        <option value="<?php echo $song['songid']; ?>" <?php if ($prefill['songid'] == $song['songid']) echo 'selected'; ?>><?php echo htmlspecialchars($song['title'] . ' - ' . $song['performer']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <a href="add_song.php" class="btn btn-secondary" style="margin-bottom: 20px;">Request a Song</a>
            <div class="form-group" style="width: 100%; margin-bottom: 20px;">
                <select name="instrumentid" id="notation-instrumentid" required style="width: 100%; height: 45px; background: #2a2a2a; color: #fff; border: 1px solid #464646; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 0 10px;">
                    <option value="">Select Instrument</option>
                    <?php foreach ($instruments as $instrument): ?>
                        <option value="<?php echo $instrument['instrumentid']; ?>" <?php if ($prefill['instrumentid'] == $instrument['instrumentid']) echo 'selected'; ?>><?php echo htmlspecialchars($instrument['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="margin-bottom: 20px;">
                <button type="button" id="tab-mode-btn" class="btn btn-secondary" style="margin-right: 10px;">Tab Editor</button>
                <button type="button" id="text-mode-btn" class="btn btn-secondary">Text Notation</button>
            </div>
            <div id="tab-editor" style="display: <?php echo $prefill_mode === 'tab' ? '' : 'none'; ?>;">
                <div class="form-group" style="width: 100%;">
                    <div id="tab-editor-controls" style="display: flex; flex-direction: column; gap: 10px; align-items: flex-start; margin-bottom: 10px;">
                        <div id="fretboard-app"></div>
                        <div style="display: flex; gap: 10px;">
                            <button type="button" id="add-chord" class="btn btn-secondary">Add Chord</button>
                            <button type="button" id="add-tact" class="btn btn-secondary">Add Bar Line</button>
                            <button type="button" id="clear-chord" class="btn btn-secondary">Clear Chord</button>
                        </div>
                    </div>
                    <div id="tab-note-list" style="display: none;"></div>
                    <input type="hidden" name="content" id="tab-json-content">
                    <div id="vf-preview" style="margin-top: 20px; margin-bottom: 20px; background: #181818; border-radius: 4px; padding: 20px; max-width: 100%;"></div>
                </div>
            </div>
            <div id="text-editor" style="display: <?php echo $prefill_mode === 'text' ? '' : 'none'; ?>;">
                <div class="form-group" style="width: 100%;">
                    <textarea name="content" id="text-content" style="width: 100%; box-sizing: border-box; background: #2a2a2a; color: #fff; border: 1px solid #464646; padding: 10px 0px 10px 10px; font-family: monospace; margin-bottom: 20px; resize: none; overflow-x: hidden; white-space: pre-line; min-height: 120px;" placeholder="Enter your notation here...">
<?php 
    if ($prefill_mode === 'text') {
        if ($edit_mode) {
            echo htmlspecialchars(str_replace('\\n', "\n", $prefill['content']));
        } else {
            echo htmlspecialchars($tab_preset);
        }
    }
?>
</textarea>
                </div>
            </div>
            <div style="display: flex; gap: 10px; margin-top: 20px; align-items: center;">
                <button type="submit" class="btn btn-primary" style="color: #fff; border: none; box-shadow: none;"><?php echo $button_text; ?></button>
                <a href="<?php echo $backLink; ?>" class="btn btn-primary" style="text-decoration: none; color: #fff; border: none; box-shadow: none;">&larr; Back</a>
                <?php if ($edit_mode): ?>
                    <button type="button" id="delete-notation-btn" class="btn btn-primary" style="background: #ff6b6b; color: #fff;">Delete Notation</button>
                <?php endif; ?>
            </div>
        </form>
        <div id="notation-message" style="margin-top: 15px;"></div>
        <?php if ($edit_mode): ?>
        <form id="delete-notation-form" method="POST" action="" style="display:none;">
            <input type="hidden" name="delete_notation" value="1">
        </form>
        <?php include 'components/notation/delete_modal.php'; ?>
        <?php endif; ?>
    </div>
    <?php include('includes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/vexflow@4.2.2/build/cjs/vexflow.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prefill data from PHP
        const prefill = <?php echo $prefill_js; ?>;
        const editMode = <?php echo $edit_mode ? 'true' : 'false'; ?>;
        const prefillMode = '<?php echo $prefill_mode; ?>';

        const titleInput = document.getElementById('notation-title');
        const songInput = document.getElementById('notation-songid');
        const instrInput = document.getElementById('notation-instrumentid');
        const tabEditor = document.getElementById('tab-editor');
        const textEditor = document.getElementById('text-editor');
        const tabModeBtn = document.getElementById('tab-mode-btn');
        const textModeBtn = document.getElementById('text-mode-btn');
        const tabJsonContent = document.getElementById('tab-json-content');
        const textContent = document.getElementById('text-content');
        const tabNoteList = document.getElementById('tab-note-list');
        const previewDiv = document.getElementById('vf-preview');
        const addChordBtn = document.getElementById('add-chord');
        const addTactBtn = document.getElementById('add-tact');
        const clearChordBtn = document.getElementById('clear-chord');
        const messageDiv = document.getElementById('notation-message');

        let tabNotes = [];
        let selectedNoteIdx = null;
        let tabCursorIdx = tabNotes.length;

        // Prefill logic
        if (editMode) {
            // Set all fields
            titleInput.value = prefill.title;
            songInput.value = prefill.songid;
            instrInput.value = prefill.instrumentid;
            // Set mode and content
            if (prefillMode === 'tab') {
                tabEditor.style.display = '';
                textEditor.style.display = 'none';
                try {
                    tabNotes = JSON.parse(prefill.content);
                } catch (e) {
                    tabNotes = [];
                }
                updateTabNoteList();
                renderVexflowTab();
                tabJsonContent.value = prefill.content;
                tabJsonContent.name = 'content';
                textContent.name = '';
            } else {
                tabEditor.style.display = 'none';
                textEditor.style.display = '';
                textContent.value = prefill.content.replace(/\\n/g, "\n");
                tabJsonContent.name = '';
                textContent.name = 'content';
            }
            // Disable mode switching in edit mode
            tabModeBtn.disabled = true;
            textModeBtn.disabled = true;
        } else {
            // Add mode: allow switching
            tabModeBtn.addEventListener('click', function() {
                tabEditor.style.display = '';
                textEditor.style.display = 'none';
                tabJsonContent.name = 'content';
                textContent.name = '';
                renderVexflowTab();
            });
            textModeBtn.addEventListener('click', function() {
                tabEditor.style.display = 'none';
                textEditor.style.display = '';
                tabJsonContent.name = '';
                textContent.name = 'content';
            });
            // Initial render for tab mode if it's the default
            if (prefillMode === 'tab') {
                updateTabNoteList();
                renderVexflowTab();
            }
        }

        // Tab editor logic
        function updateTabNoteList() {
            tabNoteList.innerHTML = tabNotes.length === 0 ? '<em>No notes yet.</em>' :
                tabNotes.map((note, idx) => {
                    if (note.tact) {
                        return `#${idx+1}: <strong>| (Tact Line)</strong> <button type='button' data-idx='${idx}' class='remove-tab-note' style='margin-left:8px;color:#ff6b6b;background:none;border:none;cursor:pointer;'>Remove</button>`;
                    } else if (Array.isArray(note.positions)) {
                        return `#${idx+1}: Chord [${note.positions.map(p => `${p.str}-${p.fret}`).join(', ')}] <button type='button' data-idx='${idx}' class='remove-tab-note' style='margin-left:8px;color:#ff6b6b;background:none;border:none;cursor:pointer;'>Remove</button>`;
                    } else {
                        return `#${idx+1}: String ${note.str}, Fret ${note.fret} <button type='button' data-idx='${idx}' class='remove-tab-note' style='margin-left:8px;color:#ff6b6b;background:none;border:none;cursor:pointer;'>Remove</button>`;
                    }
                }).join('<br>');
            tabJsonContent.value = JSON.stringify(tabNotes);
        }
        if (tabNoteList) {
            tabNoteList.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-tab-note')) {
                    const idx = parseInt(e.target.getAttribute('data-idx'));
                    tabNotes.splice(idx, 1);
                    updateTabNoteList();
                    renderVexflowTab();
                }
            });
        }
        if (addChordBtn) {
            addChordBtn.addEventListener('click', function() {
                const chord = window.fretboardApp.getCurrentChord();
                if (chord && chord.length > 0) {
                    tabNotes.splice(tabCursorIdx, 0, {
                        positions: chord.map(n => ({str: n.str, fret: n.fret}))
                    });
                    tabCursorIdx++;
                    updateTabNoteList();
                    renderVexflowTab();
                    window.fretboardApp.clearChord();
                }
            });
        }
        if (addTactBtn) {
            addTactBtn.addEventListener('click', function() {
                tabNotes.splice(tabCursorIdx, 0, {tact: true});
                tabCursorIdx++;
                updateTabNoteList();
                renderVexflowTab();
            });
        }
        if (clearChordBtn) {
            clearChordBtn.addEventListener('click', function() {
                window.fretboardApp.clearChord();
            });
        }
        function renderVexflowTab() {
            previewDiv.innerHTML = '';
            const VF = Vex.Flow;
            
            const fixedStaveWidth = 1200;
            const lineHeight = 150;
            const staveStartX = 10;
            const staveContentStartX = 80; // After TAB clef
            const staveContentEndX = fixedStaveWidth - 30; // Before end barline
            const elementSpacing = 10; // 10px between each element
            const noteWidth = 40;
            const barlineWidth = 15;
            
            // Calculate element positions manually
            let elements = [];
            let currentX = staveContentStartX + elementSpacing; // Start 10px after clef
            let currentLine = 0;
            
            // Add initial cursor at the beginning
            elements.push({
                type: 'cursor',
                x: currentX,
                line: currentLine,
                idx: 0
            });
            currentX += elementSpacing; // Move past cursor area
            
            // Process each note/chord/barline with exact positioning
            for (let i = 0; i < tabNotes.length; i++) {
                const note = tabNotes[i];
                const elementWidth = note.tact ? barlineWidth : noteWidth;
                
                // Check if we need to wrap to next line
                if (currentX + elementWidth + elementSpacing > staveContentEndX) {
                    currentLine++;
                    currentX = staveContentStartX + elementSpacing;
                    
                    // Add cursor at beginning of new line
                    elements.push({
                        type: 'cursor',
                        x: currentX,
                        line: currentLine,
                        idx: i
                    });
                    currentX += elementSpacing;
                }
                
                // Add the note/chord/barline at exact position
                elements.push({
                    type: note.tact ? 'barline' : 'note',
                    x: currentX,
                    line: currentLine,
                    noteIdx: i,
                    data: note
                });
                currentX += elementWidth + elementSpacing;
                
                // Add cursor after this element
                elements.push({
                    type: 'cursor',
                    x: currentX,
                    line: currentLine,
                    idx: i + 1
                });
                currentX += elementSpacing;
            }
            
            // Render the SVG
            const svgHeight = Math.max(currentLine + 1, 1) * lineHeight + 60;
            const renderer = new VF.Renderer(previewDiv, VF.Renderer.Backends.SVG);
            renderer.resize(fixedStaveWidth, svgHeight);
            const context = renderer.getContext();
            context.setFont('Arial', 18, '').setFillStyle('#fff');
            
            // Render each line
            for (let l = 0; l <= currentLine; l++) {
                const y = 40 + (l * lineHeight);
                const stave = new VF.TabStave(staveStartX, y, fixedStaveWidth);
                
                if (l === 0) {
                    stave.addClef('tab');
                }
                stave.setEndBarType(VF.Barline.type.SINGLE);
                stave.setContext(context).draw();
            }
            
            // Get SVG element for manual positioning
            const svg = previewDiv.querySelector('svg');
            if (svg) {
                // Manually render each note/chord/barline at calculated positions
                elements.forEach(el => {
                    if (el.type === 'note') {
                        const y = 40 + (el.line * lineHeight);
                        const staffStartY = y + 34; // Position within staff lines
                        
                        if (Array.isArray(el.data.positions)) {
                            // Render chord
                            el.data.positions.forEach(pos => {
                                const stringY = staffStartY + ((6 - pos.str) * 13); // Increased spacing for more vertical space between chord notes
                                const ns = 'http://www.w3.org/2000/svg';
                                const text = document.createElementNS(ns, 'text');
                                text.textContent = pos.fret;
                                text.setAttribute('x', el.x + 10);
                                text.setAttribute('y', stringY + 23);
                                text.setAttribute('fill', selectedNoteIdx === el.noteIdx ? '#ff6b6b' : '#fff');
                                text.setAttribute('font-size', '14');
                                text.setAttribute('font-family', 'Arial, sans-serif');
                                text.setAttribute('text-anchor', 'middle');
                                text.style.cursor = 'pointer';
                                text.addEventListener('click', function(e) {
                                    e.stopPropagation();
                                    selectedNoteIdx = el.noteIdx;
                                    renderVexflowTab();
                                });
                                svg.appendChild(text);
                            });
                        } else {
                            // Render single note
                            const stringY = staffStartY + ((6 - el.data.str) * 13); // Increased spacing to match chord spacing
                            const ns = 'http://www.w3.org/2000/svg';
                            const text = document.createElementNS(ns, 'text');
                            text.textContent = el.data.fret;
                            text.setAttribute('x', el.x);
                            text.setAttribute('y', stringY);
                            text.setAttribute('fill', selectedNoteIdx === el.noteIdx ? '#ff6b6b' : '#fff');
                            text.setAttribute('font-size', '14');
                            text.setAttribute('font-family', 'Arial, sans-serif');
                            text.setAttribute('text-anchor', 'middle');
                            text.style.cursor = 'pointer';
                            text.addEventListener('click', function(e) {
                                e.stopPropagation();
                                selectedNoteIdx = el.noteIdx;
                                renderVexflowTab();
                            });
                            svg.appendChild(text);
                        }
                        
                        // Add delete button if selected
                        if (selectedNoteIdx === el.noteIdx) {
                            const ns = 'http://www.w3.org/2000/svg';
                            const xBtn = document.createElementNS(ns, 'text');
                            xBtn.textContent = 'x';
                            xBtn.setAttribute('x', el.x + 10);
                            let xBtnY;
                            if (Array.isArray(el.data.positions)) {
                                // For chords, find the bottom note (highest string number)
                                const maxString = Math.max(...el.data.positions.map(p => p.str));
                                const staffStartY = 40 + (el.line * lineHeight) + 12;
                                xBtnY = staffStartY + ((6 - maxString) * 13) + 23;
                            } else {
                                // For single notes, use the note's y
                                xBtnY = y + 70;
                            }
                            xBtn.setAttribute('y', xBtnY);
                            xBtn.setAttribute('fill', '#ff6b6b');
                            xBtn.setAttribute('font-size', '14');
                            xBtn.setAttribute('font-family', 'Arial, sans-serif');
                            xBtn.setAttribute('text-anchor', 'middle');
                            xBtn.style.cursor = 'pointer';
                            xBtn.addEventListener('click', function(e) {
                                e.stopPropagation();
                                tabNotes.splice(el.noteIdx, 1);
                                selectedNoteIdx = null;
                                if (tabCursorIdx > el.noteIdx) tabCursorIdx--;
                                updateTabNoteList();
                                renderVexflowTab();
                            });
                            svg.appendChild(xBtn);
                        }
                    } else if (el.type === 'barline') {
                        // Render barline
                        const y = 40 + (el.line * lineHeight);
                        const ns = 'http://www.w3.org/2000/svg';
                        const line = document.createElementNS(ns, 'line');
                        line.setAttribute('x1', el.x + 10);
                        line.setAttribute('x2', el.x + 10);
                        line.setAttribute('y1', y + 52);
                        line.setAttribute('y2', y + 117); // 50px span for 6 strings with 10px spacing
                        line.setAttribute('stroke', '#fff');
                        line.setAttribute('stroke-width', '2');
                        svg.appendChild(line);
                    } else if (el.type === 'cursor') {
                        const y = 40 + (el.line * lineHeight);
                        
                        // Draw cursor line if this is the active cursor
                        if (el.idx === tabCursorIdx) {
                            const ns = 'http://www.w3.org/2000/svg';
                            const line = document.createElementNS(ns, 'line');
                            line.setAttribute('x1', el.x);
                            line.setAttribute('x2', el.x+1);
                            line.setAttribute('y1', y + 52); // Start at first string
                            line.setAttribute('y2', y + 117); // End at last string (50px span)
                            line.setAttribute('stroke', '#4faaff');
                            line.setAttribute('stroke-width', '2');
                            svg.appendChild(line);
                        }
                        
                        // Add clickable area for cursor positioning
                        const ns = 'http://www.w3.org/2000/svg';
                        const rect = document.createElementNS(ns, 'rect');
                        rect.setAttribute('x', el.x - 7.5);
                        rect.setAttribute('y', y + 45);
                        rect.setAttribute('width', 15);
                        rect.setAttribute('height', 77); // Cover the tab staff area
                        rect.setAttribute('fill', 'transparent');
                        rect.style.cursor = 'pointer';
                        rect.addEventListener('click', function(e) {
                            e.stopPropagation();
                            tabCursorIdx = el.idx;
                            selectedNoteIdx = null;
                            renderVexflowTab();
                        });
                        svg.appendChild(rect);
                    }
                });
            }
        }
        // Form submission logic (AJAX)
        document.getElementById('notation-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const isTabNotation = tabEditor.style.display !== 'none';
            let changed = false;
            if (titleInput.value !== prefill.title) changed = true;
            if (songInput.value !== String(prefill.songid)) changed = true;
            if (instrInput.value !== String(prefill.instrumentid)) changed = true;
            if (isTabNotation) {
                if (JSON.stringify(tabNotes) !== prefill.content) changed = true;
            } else {
                if (textContent.value !== prefill.content) changed = true;
            }
            if (editMode && !changed) {
                messageDiv.style.color = '#ff6b6b';
                messageDiv.textContent = 'No changes to save';
                return;
            }
            messageDiv.textContent = editMode ? 'Saving changes...' : 'Saving...';
            messageDiv.style.color = '#4faaff';
            try {
                const response = await fetch(window.location.pathname + window.location.search, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const text = await response.text();
                let result;
                try {
                    result = JSON.parse(text);
                } catch (err) {
                    throw new Error('Invalid server response: ' + text);
                }
                if (result.success) {
                    messageDiv.style.color = '#4caf50';
                    messageDiv.textContent = editMode ? 'Notation updated successfully! Redirecting...' : 'Notation added successfully! Redirecting...';
                    setTimeout(() => {
                        window.location.href = 'notation.php?id=' + result.id + '&from=mynotations&back=mynotations.php';
                    }, 1000);
                } else {
                    messageDiv.style.color = '#ff6b6b';
                    messageDiv.textContent = result.message || (editMode ? 'Error updating notation.' : 'Error adding notation.');
                }
            } catch (err) {
                messageDiv.style.color = '#ff6b6b';
                messageDiv.textContent = 'Network or server error. ' + err.message;
            }
        });
    });
    </script>
    <script>
    const { createApp, ref } = Vue;

    const app = createApp({
      setup() {
        // 6 strings (1 = Low E, 6 = High e), 0-22 frets
        const NUM_STRINGS = 6;
        const NUM_FRETS = 22;
        // Chord being built: array of {str, fret}
        const currentChord = ref([]);

        // Returns true if this string/fret is selected
        function isSelected(string, fret) {
          return currentChord.value.some(n => n.str === string && n.fret === fret);
        }

        // Handle click on a fret
        function toggleFret(string, fret) {
          const idx = currentChord.value.findIndex(n => n.str === string);
          if (idx !== -1 && currentChord.value[idx].fret === fret) {
            // Deselect
            currentChord.value.splice(idx, 1);
          } else {
            // Only one note per string
            if (idx !== -1) currentChord.value.splice(idx, 1);
            currentChord.value.push({str: string, fret: fret});
          }
        }

        // Clear chord
        function clearChord() {
          currentChord.value = [];
        }

        // For display
        function chordText() {
          if (currentChord.value.length === 0) return 'None';
          return currentChord.value
            .sort((a, b) => b.str - a.str)
            .map(n => `${n.str}-${n.fret}`).join(', ');
        }

        // Expose methods to window
        window.fretboardApp = {
          getCurrentChord: () => currentChord.value,
          clearChord: clearChord
        };

        return {
          NUM_STRINGS,
          NUM_FRETS,
          currentChord,
          isSelected,
          toggleFret,
          clearChord,
          chordText
        };
      },
    //  FRETBOARD OUTPUT TEMPLATE
      template: `   
        <div>
          <div style="display: grid; grid-template-columns: repeat(22, 32px); grid-template-rows: repeat(6, 32px); gap: 2px; margin-bottom: 10px; background: #232323; border-radius: 4px; border: 1px solid #464646; padding: 8px 8px 8px 8px;">
            <template v-for="string in [...Array(NUM_STRINGS).keys()].map(i => NUM_STRINGS - i)">
              <template v-for="fret in Array.from({length: NUM_FRETS}, (_, i) => i)">
                <div
                  :key="string + '-' + fret"
                  :style="{
                    width: '32px',
                    height: '32px',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    cursor: 'pointer',
                    background: isSelected(string, fret) ? '#4faaff' : (fret === 0 ? '#232323' : '#2a2a2a'),
                    color: isSelected(string, fret) ? '#fff' : '#aaa',
                    border: '1px solid #464646'
                  }"
                  @click="toggleFret(string, fret)"
                  :title="\`String \${string}, Fret \${fret}\`"
                >{{ fret === 0 ? '0' : fret }}</div>
              </template>
            </template>
          </div>
        </div>
      `
    }).mount('#fretboard-app');
    </script>
</body>
</html>


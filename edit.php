<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
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
$song_sql = "SELECT * FROM songs";
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
$prefill_mode = 'text';
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
}

// Set the back link for the form
if ($edit_mode && $notation) {
    $from = isset($_GET['from']) ? $_GET['from'] : '';
    $back = isset($_GET['back']) ? $_GET['back'] : '';
    // Always set backLink to the interactable notation page
    $backLink = 'notation.php?id=' . $notation_id . '&from=mynotations&back=mynotations.php';
} else {
    $from = isset($_GET['from']) ? $_GET['from'] : '';
    $backLink = ($from === 'dashboard') ? 'dashboard.php' : 'mynotations.php';
}
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
    <div class="wrapper" style="width: 100%; max-width: 1200px; margin: 0 auto; overflow-x: auto;">
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
            <div class="form-group" style="width: 100%; margin-bottom: 20px;">
                <select name="instrumentid" id="notation-instrumentid" required style="width: 100%; height: 45px; background: #2a2a2a; color: #fff; border: 1px solid #464646; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 0 10px;">
                    <option value="">Select Instrument</option>
                    <?php foreach ($instruments as $instrument): ?>
                        <option value="<?php echo $instrument['instrumentid']; ?>" <?php if ($prefill['instrumentid'] == $instrument['instrumentid']) echo 'selected'; ?>><?php echo htmlspecialchars($instrument['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="margin-bottom: 20px;">
                <button type="button" id="tab-mode-btn" class="btn" style="margin-right: 10px; <?php if ($prefill_mode === 'tab') echo 'background: #4faaff; color: #fff;'; ?>">Tab Editor</button>
                <button type="button" id="text-mode-btn" class="btn" <?php if ($prefill_mode === 'text') echo 'style="background: #4faaff; color: #fff;"'; ?>>Text Notation</button>
            </div>
            <div id="tab-editor" style="display: <?php echo $prefill_mode === 'tab' ? '' : 'none'; ?>;">
                <div class="form-group" style="width: 100%;">
                    <div id="tab-editor-controls" style="display: flex; flex-direction: column; gap: 10px; align-items: flex-start; margin-bottom: 10px;">
                        <div id="fretboard-app"></div>
                        <div style="display: flex; gap: 10px;">
                            <button type="button" id="add-chord" class="btn btn-primary">Add Chord</button>
                            <button type="button" id="add-tact" class="btn btn-primary">Add Tact Line</button>
                            <button type="button" id="clear-chord" class="btn">Clear Chord</button>
                        </div>
                    </div>
                    <div id="tab-note-list" style="margin-bottom: 10px; color: #ccc; font-size: 0.95rem;"></div>
                    <input type="hidden" name="content" id="tab-json-content">
                    <div id="vf-preview" style="margin-top: 20px; margin-bottom: 20px; background: #181818; border-radius: 4px; padding: 20px; max-width: 100%;"></div>
                </div>
            </div>
            <div id="text-editor" style="display: <?php echo $prefill_mode === 'text' ? '' : 'none'; ?>;">
                <div class="form-group" style="width: 100%;">
                    <textarea name="content" id="text-content" style="width: 100%; box-sizing: border-box; height: 300px; background: #2a2a2a; color: #fff; border: 1px solid #464646; padding: 10px; font-family: monospace; margin-bottom: 20px; resize: none; overflow-x: hidden; white-space: pre-line;" placeholder="Enter your notation here..."><?php echo $prefill_mode === 'text' ? htmlspecialchars($prefill['content']) : ''; ?></textarea>
                </div>
            </div>
            <div style="display: flex; gap: 20px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary"><?php echo $button_text; ?></button>
                <a href="<?php echo $backLink; ?>" class="btn btn-primary" style="text-decoration: none;">&larr; Back</a>
            </div>
        </form>
        <div id="notation-message" style="margin-top: 15px;"></div>
    </div>
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
        let selectedNoteIdx = null; // Track selected note index for deletion
        let tabCursorIdx = tabNotes.length; // Cursor defaults to end
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
                tabModeBtn.style.background = '#4faaff';
                tabModeBtn.style.color = '#fff';
                textModeBtn.style.background = '';
                textModeBtn.style.color = '';
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
                textModeBtn.style.background = '#4faaff';
                textModeBtn.style.color = '#fff';
                tabModeBtn.style.background = '';
                tabModeBtn.style.color = '';
                textContent.value = prefill.content;
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
                tabModeBtn.style.background = '#4faaff';
                tabModeBtn.style.color = '#fff';
                textModeBtn.style.background = '';
                textModeBtn.style.color = '';
                tabJsonContent.name = 'content';
                textContent.name = '';
            });
            textModeBtn.addEventListener('click', function() {
                tabEditor.style.display = 'none';
                textEditor.style.display = '';
                textModeBtn.style.background = '#4faaff';
                textModeBtn.style.color = '#fff';
                tabModeBtn.style.background = '';
                tabModeBtn.style.color = '';
                tabJsonContent.name = '';
                textContent.name = 'content';
            });
        }

        // --- Tab editor logic (same as before) ---
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
        tabNoteList.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-tab-note')) {
                const idx = parseInt(e.target.getAttribute('data-idx'));
                tabNotes.splice(idx, 1);
                updateTabNoteList();
                renderVexflowTab();
            }
        });
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
        addTactBtn.addEventListener('click', function() {
            tabNotes.splice(tabCursorIdx, 0, {tact: true});
            tabCursorIdx++;
            updateTabNoteList();
            renderVexflowTab();
        });
        clearChordBtn.addEventListener('click', function() {
            window.fretboardApp.clearChord();
        });
        function splitNotesIntoLines(notes, maxNotesPerLine = 16) {
            let lines = [];
            let currentLine = [];
            let noteCount = 0;
            for (let i = 0; i < notes.length; i++) {
                currentLine.push(notes[i]);
                noteCount++;
                if (notes[i].tact && noteCount >= maxNotesPerLine) {
                    lines.push(currentLine);
                    currentLine = [];
                    noteCount = 0;
                }
            }
            if (currentLine.length > 0) lines.push(currentLine);
            return lines;
        }
        function renderVexflowTab() {
            previewDiv.innerHTML = '';
            const VF = Vex.Flow;
            const lines = splitNotesIntoLines(tabNotes, 16); // You can adjust max notes per line
            const fixedStaveWidth = 1100;
            const lineHeight = 200;
            const svgHeight = lines.length * lineHeight + 60;
            const renderer = new VF.Renderer(previewDiv, VF.Renderer.Backends.SVG);
            renderer.resize(fixedStaveWidth, svgHeight);
            const context = renderer.getContext();
            context.setFont('Arial', 18, '').setFillStyle('#fff');
            let y = 40;
            let noteIdx = 0; // Track index in tabNotes
            let noteIndices = [];
            tabNotes.forEach((note, idx) => {
                if (!note.tact) noteIndices.push(idx);
            });
            // For cursor overlay
            let cursorSpots = [];
            let runningIdx = 0;
            for (let l = 0; l < lines.length; l++) {
                let lineNotes = lines[l];
                const stave = new VF.TabStave(10, y, fixedStaveWidth);
                if (l === 0) {
                    stave.addClef('tab');
                }
                stave.setContext(context).draw();
                let notes = [];
                lineNotes.forEach((note) => {
                    if (note.tact) {
                        notes.push(new VF.BarNote());
                    } else {
                        let positions = Array.isArray(note.positions) ? note.positions : [{ str: 7 - note.str, fret: note.fret }];
                        let tabNote = new VF.TabNote({ positions: positions, duration: 'q' }).setStyle({ fillStyle: '#fff', strokeStyle: '#fff' });
                        tabNote.noteIdx = noteIdx;
                        if (selectedNoteIdx === noteIdx) {
                            tabNote.setStyle({ fillStyle: '#ff6b6b', strokeStyle: '#ff6b6b' });
                        }
                        notes.push(tabNote);
                    }
                    noteIdx++;
                });
                if (notes.length > 0) {
                    const voice = new VF.Voice().setStrict(false);
                    voice.addTickables(notes);
                    new VF.Formatter().joinVoices([voice]).format([voice], fixedStaveWidth - 60);
                    voice.draw(context, stave);
                    // Cursor at the very beginning
                    let prevX = stave.getNoteStartX();
                    cursorSpots.push({ idx: runningIdx, x: prevX, y: y, h: lineHeight });
                    // Cursor after each chord or barline
                    let tickables = voice.getTickables();
                    for (let i = 0; i < tickables.length; i++) {
                        let bb = tickables[i].getBoundingBox();
                        let x = (bb && bb.getX() !== undefined && bb.getW() !== undefined) ? (bb.getX() + bb.getW() + 10) : (prevX + 50);
                        cursorSpots.push({ idx: runningIdx + i + 1, x: x, y: y, h: lineHeight });
                        prevX = x;
                    }
                    runningIdx += notes.length;
                } else {
                    // If no notes, put a cursor at the start
                    cursorSpots.push({ idx: runningIdx, x: stave.getNoteStartX(), y: y, h: lineHeight });
                }
                y += lineHeight;
            }
            // After rendering, overlay cursor and clickable areas
            const svg = previewDiv.querySelector('svg');
            if (svg) {
                // Draw cursor line at selected position
                let spot = cursorSpots.find(s => s.idx === tabCursorIdx);
                if (spot) {
                    const ns = 'http://www.w3.org/2000/svg';
                    const line = document.createElementNS(ns, 'line');
                    line.setAttribute('x1', spot.x);
                    line.setAttribute('x2', spot.x);
                    line.setAttribute('y1', spot.y);
                    line.setAttribute('y2', spot.y + spot.h - 20);
                    line.setAttribute('stroke', '#4faaff');
                    line.setAttribute('stroke-width', '3');
                    line.setAttribute('class', 'tab-cursor');
                    svg.appendChild(line);
                }
                // Overlay transparent rects for cursor movement
                cursorSpots.forEach((spot, i) => {
                    const ns = 'http://www.w3.org/2000/svg';
                    const rect = document.createElementNS(ns, 'rect');
                    rect.setAttribute('x', spot.x - 10);
                    rect.setAttribute('y', spot.y);
                    rect.setAttribute('width', 20);
                    rect.setAttribute('height', spot.h - 20);
                    rect.setAttribute('fill', 'transparent');
                    rect.style.cursor = 'pointer';
                    rect.addEventListener('click', function(e) {
                        e.stopPropagation();
                        tabCursorIdx = spot.idx;
                        selectedNoteIdx = null;
                        renderVexflowTab();
                    });
                    svg.appendChild(rect);
                });
                // ... (existing note click/X logic follows here)
                // Remove all rectangles (white backgrounds) in this group
                const tabNoteGroups = svg.querySelectorAll('g.vf-tabnote');
                tabNoteGroups.forEach((group, i) => {
                    const rects = group.querySelectorAll('rect');
                    rects.forEach(rect => rect.remove());
                });
                tabNoteGroups.forEach((group, i) => {
                    const noteIdx = noteIndices[i];
                    if (noteIdx !== undefined) {
                        const texts = group.querySelectorAll('text');
                        texts.forEach(text => {
                            text.style.cursor = 'pointer';
                            text.setAttribute('data-note-idx', noteIdx);
                            text.addEventListener('click', function(e) {
                                e.stopPropagation();
                                selectedNoteIdx = noteIdx;
                                renderVexflowTab();
                            });
                        });
                        if (selectedNoteIdx === noteIdx) {
                            let topText = null;
                            let minY = Infinity;
                            texts.forEach(t => {
                                const y = parseFloat(t.getAttribute('y'));
                                if (y < minY) {
                                    minY = y;
                                    topText = t;
                                }
                            });
                            if (topText) {
                                const bbox = topText.getBBox();
                                const ns = 'http://www.w3.org/2000/svg';
                                const xBtn = document.createElementNS(ns, 'text');
                                xBtn.textContent = '×';
                                xBtn.setAttribute('x', bbox.x + bbox.width / 2);
                                xBtn.setAttribute('y', bbox.y - 8);
                                xBtn.setAttribute('fill', '#ff6b6b');
                                xBtn.setAttribute('font-size', bbox.height * 0.9);
                                xBtn.setAttribute('font-family', 'Arial, sans-serif');
                                xBtn.setAttribute('text-anchor', 'middle');
                                xBtn.style.cursor = 'pointer';
                                xBtn.addEventListener('click', function(e) {
                                    e.stopPropagation();
                                    tabNotes.splice(noteIdx, 1);
                                    selectedNoteIdx = null;
                                    if (tabCursorIdx > noteIdx) tabCursorIdx--;
                                    updateTabNoteList();
                                    renderVexflowTab();
                                });
                                group.appendChild(xBtn);
                            }
                        }
                    }
                });
            }
        }
        // --- Form submission logic (AJAX) ---
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
        // 6 strings (1 = high e, 6 = low E), 0-12 frets
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
          <div style="display: grid; grid-template-columns: repeat(22, 32px); grid-template-rows: repeat(6, 32px); gap: 2px; margin-bottom: 10px; background: #232323; border-radius: 4px; border: 1px solid #464646; padding: 8px 0 8px 8px;">
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
          <div style="margin-bottom: 10px; color: #ccc; font-size: 0.95rem;">
            Current Chord: <span>{{ chordText() }}</span>
          </div>
        </div>
      `
    }).mount('#fretboard-app');
    </script>
</body>
</html>


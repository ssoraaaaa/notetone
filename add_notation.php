<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
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

    // If it's not valid JSON, treat it as plain text
    if (!$is_tab) {
        $content = $conn->real_escape_string($content);
    }

    $sql = "INSERT INTO notations (title, dateadded, content, songid, instrumentid, userid) VALUES ('$title', '$dateadded', '$content', '$songid', '$instrumentid', '$userid')";
    if ($conn->query($sql) === TRUE) {
        if ($is_ajax) {
            echo json_encode(['success' => true]);
            exit;
        }
        header('Location: notations.php');
        exit;
    } else {
        if ($is_ajax) {
            echo json_encode(['success' => false, 'message' => $conn->error]);
            exit;
        }
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Notation - NoteTone</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="wrapper" style="width: 100%; max-width: 1200px; margin: 0 auto; overflow-x: auto;">
        <h2 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">Add Notation</h2>
        <form id="notation-form" method="POST" action="">
            <div id="step1">
            <div class="form-group" style="width: 100%;">
                    <input type="text" name="title" id="notation-title" class="form-control" placeholder="Title" required style="background: #2a2a2a; color: #fff; border: 1px solid #464646; margin-bottom: 20px; width: 100%; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; height: 45px;">
            </div>
            <div class="form-group" style="width: 100%; margin-bottom: 20px;">
                    <select name="songid" id="notation-songid" required style="width: 100%; height: 45px; background: #2a2a2a; color: #fff; border: 1px solid #464646; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 0 10px;">
                    <option value="">Select Song</option>
                    <?php foreach ($songs as $song): ?>
                        <option value="<?php echo $song['songid']; ?>"><?php echo htmlspecialchars($song['title'] . ' - ' . $song['performer']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="width: 100%; margin-bottom: 20px;">
                    <select name="instrumentid" id="notation-instrumentid" required style="width: 100%; height: 45px; background: #2a2a2a; color: #fff; border: 1px solid #464646; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 0 10px;">
                    <option value="">Select Instrument</option>
                    <?php foreach ($instruments as $instrument): ?>
                        <option value="<?php echo $instrument['instrumentid']; ?>"><?php echo htmlspecialchars($instrument['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
                <button type="button" id="next-to-type" class="btn btn-primary">Next</button>
            </div>

            <div id="step2" style="display:none;">
                <h3 style="color: #fff; margin-bottom: 20px;">Choose Notation Type</h3>
                <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                    <div class="notation-type-option" style="flex: 1; background: #2a2a2a; border: 1px solid #464646; border-radius: 4px; padding: 20px; cursor: pointer;" onclick="selectNotationType('tab')">
                        <h4 style="color: #fff; margin-bottom: 10px;">Tab Editor</h4>
                        <p style="color: #ccc;">Create interactive guitar tabs with our visual editor. Perfect for guitarists!</p>
                    </div>
                    <div class="notation-type-option" style="flex: 1; background: #2a2a2a; border: 1px solid #464646; border-radius: 4px; padding: 20px; cursor: pointer;" onclick="selectNotationType('text')">
                        <h4 style="color: #fff; margin-bottom: 10px;">Text Notation</h4>
                        <p style="color: #ccc;">Write your notation in plain text. Great for lyrics, chords, or any text-based notation.</p>
                    </div>
                </div>
                <button type="button" id="back-to-info" class="btn" style="margin-right: 10px;">Back</button>
            </div>

            <div id="step3" style="display:none;">
                <div id="tab-editor" style="display:none;">
                    <div class="form-group" style="width: 100%;">
                        <div id="tab-editor-controls" style="display: flex; flex-direction: column; gap: 10px; align-items: flex-start; margin-bottom: 10px;">
                            <div style="display: flex; gap: 20px;">
                                <label>Time Signature:
                                    <select id="time-signature" style="background: #2a2a2a; color: #fff; border: 1px solid #464646;">
                                        <option value="4/4">4/4</option>
                                        <option value="3/4">3/4</option>
                                        <option value="2/4">2/4</option>
                                        <option value="6/8">6/8</option>
                                    </select>
                                </label>
                                <label>Duration:
                                    <select id="tab-duration" style="background: #2a2a2a; color: #fff; border: 1px solid #464646;">
                                        <option value="w">Whole</option>
                                        <option value="h">Half</option>
                                        <option value="h.">Dotted Half</option>
                                        <option value="q">Quarter</option>
                                        <option value="q.">Dotted Quarter</option>
                                        <option value="8">Eighth</option>
                                        <option value="8.">Dotted Eighth</option>
                                        <option value="16">Sixteenth</option>
                                        <option value="16.">Dotted Sixteenth</option>
                                        <option value="8t">Eighth Triplet</option>
                                        <option value="16t">Sixteenth Triplet</option>
                                        <option value="16s">Sixteenth Sixtuplet</option>
                                    </select>
                                </label>
                            </div>
                            <div id="fretboard-app"></div>
                            <div style="display: flex; gap: 10px;">
                                <button type="button" id="add-chord" class="btn btn-primary">Add Chord</button>
                                <button type="button" id="clear-chord" class="btn">Clear Chord</button>
                            </div>
                        </div>
                        <div id="tab-note-list" style="margin-bottom: 10px; color: #ccc; font-size: 0.95rem;"></div>
                        <input type="hidden" name="content" id="tab-json-content">
                        <div id="vf-preview" style="margin-top: 20px; margin-bottom: 20px; background: #181818; border-radius: 4px; padding: 20px; max-width: 100%; overflow-x: auto; overflow-y: visible; white-space: nowrap;"></div>
                    </div>
                </div>

                <div id="text-editor" style="display:none;">
                    <div class="form-group" style="width: 100%;">
                        <textarea name="content" id="text-content" style="width: 100%; height: 300px; background: #2a2a2a; color: #fff; border: 1px solid #464646; padding: 10px; font-family: monospace; margin-bottom: 20px; resize: none; white-space: pre-wrap; word-wrap: break-word;" placeholder="Enter your notation here..."></textarea>
                    </div>
                </div>

                <div style="display: flex; gap: 20px; margin-top: 20px;">
                    <button type="button" id="back-to-type" class="btn">Back</button>
            <button type="submit" class="btn btn-primary">Add Notation</button>
                </div>
            </div>
        </form>
        <div id="notation-message" style="margin-top: 15px;"></div>
    </div>
    <!-- Place scripts at the end of body for proper loading -->
    <script src="https://cdn.jsdelivr.net/npm/vexflow@4.2.2/build/cjs/vexflow.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Step navigation logic
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');
        const nextToTypeBtn = document.getElementById('next-to-type');
        const backToInfoBtn = document.getElementById('back-to-info');
        const backToTypeBtn = document.getElementById('back-to-type');
        const titleInput = document.getElementById('notation-title');
        const songInput = document.getElementById('notation-songid');
        const instrInput = document.getElementById('notation-instrumentid');
        const messageDiv = document.getElementById('notation-message');
        const tabEditor = document.getElementById('tab-editor');
        const textEditor = document.getElementById('text-editor');

        // --- AUTOSAVE/RESTORE LOGIC ---
        let tabNotes = [];
        const tabDuration = document.getElementById('tab-duration');
        const timeSignature = document.getElementById('time-signature');
        const addChordBtn = document.getElementById('add-chord');
        const clearChordBtn = document.getElementById('clear-chord');
        const tabNoteList = document.getElementById('tab-note-list');
        const tabJsonContent = document.getElementById('tab-json-content');
        const previewDiv = document.getElementById('vf-preview');
        const textContent = document.getElementById('text-content');

        // Restore draft if exists
        let draft = localStorage.getItem('notationDraft');
        if (draft) {
            try {
                draft = JSON.parse(draft);
                if (draft.title) titleInput.value = draft.title;
                if (draft.songid) songInput.value = draft.songid;
                if (draft.instrumentid) instrInput.value = draft.instrumentid;
                if (draft.tabNotes) tabNotes = draft.tabNotes;
                if (draft.timeSignature) timeSignature.value = draft.timeSignature;
                if (draft.textContent) textContent.value = draft.textContent;
                if (draft.notationType) {
                    selectNotationType(draft.notationType);
                }
                if (draft.step === '2') {
                    step1.style.display = 'none';
                    step2.style.display = '';
                    step3.style.display = 'none';
                } else if (draft.step === '3') {
                    step1.style.display = 'none';
                    step2.style.display = 'none';
                    step3.style.display = '';
                } else {
                    step1.style.display = '';
                    step2.style.display = 'none';
                    step3.style.display = 'none';
                }
            } catch (e) {
                localStorage.removeItem('notationDraft');
            }
        } else {
            step1.style.display = '';
            step2.style.display = 'none';
            step3.style.display = 'none';
        }

        function saveDraft(stepOverride) {
            localStorage.setItem('notationDraft', JSON.stringify({
                title: titleInput.value,
                songid: songInput.value,
                instrumentid: instrInput.value,
                tabNotes: tabNotes,
                timeSignature: timeSignature.value,
                textContent: textContent.value,
                notationType: tabEditor.style.display !== 'none' ? 'tab' : 'text',
                step: stepOverride || (step3.style.display !== 'none' ? '3' : (step2.style.display !== 'none' ? '2' : '1'))
            }));
        }

        // Save on input changes
        titleInput.addEventListener('input', function() { saveDraft(); });
        songInput.addEventListener('change', function() { saveDraft(); });
        instrInput.addEventListener('change', function() { saveDraft(); });
        timeSignature.addEventListener('change', function() { 
            saveDraft();
            renderVexflowTab();
        });
        textContent.addEventListener('input', function() { saveDraft(); });

        function selectNotationType(type) {
            // Show step 3
            step1.style.display = 'none';
            step2.style.display = 'none';
            step3.style.display = '';

            if (type === 'tab') {
                tabEditor.style.display = '';
                textEditor.style.display = 'none';
                document.getElementById('tab-json-content').name = 'content';
                document.getElementById('text-content').name = '';
            } else {
                tabEditor.style.display = 'none';
                textEditor.style.display = '';
                document.getElementById('tab-json-content').name = '';
                document.getElementById('text-content').name = 'content';
            }
            saveDraft('3');
        }

        window.selectNotationType = selectNotationType;

        nextToTypeBtn.addEventListener('click', function() {
            // Validate step 1
            if (!titleInput.value.trim()) {
                messageDiv.textContent = 'Please enter a title.';
                messageDiv.style.color = '#ff6b6b';
                return;
            }
            if (!songInput.value) {
                messageDiv.textContent = 'Please select a song.';
                messageDiv.style.color = '#ff6b6b';
                return;
            }
            if (!instrInput.value) {
                messageDiv.textContent = 'Please select an instrument.';
                messageDiv.style.color = '#ff6b6b';
                return;
            }
            messageDiv.textContent = '';
            step1.style.display = 'none';
            step2.style.display = '';
            step3.style.display = 'none';
            saveDraft('2');
        });

        backToInfoBtn.addEventListener('click', function() {
            step2.style.display = 'none';
            step1.style.display = '';
            step3.style.display = 'none';
            saveDraft('1');
        });

        backToTypeBtn.addEventListener('click', function() {
            step3.style.display = 'none';
            step2.style.display = '';
            step1.style.display = 'none';
            saveDraft('2');
        });

        function getDurationInBeats(duration, tuplet) {
            let base = 1;
            switch(duration.replace('.', '')) {
                case 'w': base = 4; break;
                case 'h': base = 2; break;
                case 'q': base = 1; break;
                case '8': base = 0.5; break;
                case '16': base = 0.25; break;
            }
            if (duration.endsWith('.')) base *= 1.5;
            if (tuplet === 3) base *= 2/3; // triplet
            if (tuplet === 6) base *= 1/3; // sixtuplet
            return base;
        }

        function updateTabNoteList() {
            tabNoteList.innerHTML = tabNotes.length === 0 ? '<em>No notes yet.</em>' :
                tabNotes.map((note, idx) => {
                    if (Array.isArray(note.positions)) {
                        // Chord
                        return `#${idx+1}: Chord [${note.positions.map(p => `${p.str}-${p.fret}`).join(', ')}], Duration ${note.duration} <button type='button' data-idx='${idx}' class='remove-tab-note' style='margin-left:8px;color:#ff6b6b;background:none;border:none;cursor:pointer;'>Remove</button>`;
                    } else {
                        // Single note
                        return `#${idx+1}: String ${note.str}, Fret ${note.fret}, Duration ${note.duration} <button type='button' data-idx='${idx}' class='remove-tab-note' style='margin-left:8px;color:#ff6b6b;background:none;border:none;cursor:pointer;'>Remove</button>`;
                    }
                }).join('<br>');
            tabJsonContent.value = JSON.stringify(tabNotes);
            saveDraft();
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
            const rawDuration = tabDuration.value;
            let duration = rawDuration;
            let tuplet = null;
            if (duration.endsWith('t')) {
                tuplet = 3; // triplet
                duration = duration.replace('t', '');
            } else if (duration.endsWith('s')) {
                tuplet = 6; // sixtuplet
                duration = duration.replace('s', '');
            }
            // Get the current chord from Vue component
            const chord = window.fretboardApp.getCurrentChord();
            if (chord && chord.length > 0) {
                tabNotes.push({
                    positions: chord.map(n => ({str: n.str, fret: n.fret})),
                    duration: duration,
                    tuplet: tuplet
                });
                updateTabNoteList();
                renderVexflowTab();
                window.fretboardApp.clearChord();
            }
        });

        clearChordBtn.addEventListener('click', function() {
            console.log('Clear Chord clicked');
            window.fretboardApp.clearChord();
        });

        function renderVexflowTab() {
            previewDiv.innerHTML = '';
            const VF = Vex.Flow;
            // Use the container's width dynamically
            const containerWidth = previewDiv.clientWidth || 700;

            // Parse time signature (still needed for measure calculation)
            const [beats, beatType] = timeSignature.value.split('/').map(Number);
            
            // Group notes into measures strictly by time signature
            let measures = [];
            let currentMeasure = [];
            let currentBeats = 0;
            tabNotes.forEach(note => {
                const noteBeats = getDurationInBeats(note.duration, note.tuplet);
                if (currentBeats + noteBeats > beats) {
                    measures.push(currentMeasure);
                    currentMeasure = [note];
                    currentBeats = noteBeats;
                } else {
                    currentMeasure.push(note);
                    currentBeats += noteBeats;
                }
            });
            if (currentMeasure.length > 0) measures.push(currentMeasure);

            // Responsive measure width with minimum note spacing
            const baseWidth = 100;
            const widthPerNote = 50;
            const minWidth = 50;
            const maxWidth = 1000;
            const sidePadding = 20; // px
            const extraDigitWidth = 10; // px per digit above 1
            const minNoteSpacing = 20; // px between notes

            // Calculate stave widths for each measure
            const staveWidths = measures.map(measure => {
                const noteCount = measure.length;
                const minSpacingWidth = minNoteSpacing * (noteCount + 1); // +1 for space after last note

                // Existing code to calculate width for fret numbers, padding, etc.
                let maxFretLen = 1;
                measure.forEach(note => {
                    let frets = [];
                    if (Array.isArray(note.positions)) {
                        frets = note.positions.map(p => String(p.fret));
                    } else {
                        frets = [String(note.fret)];
                    }
                    frets.forEach(fret => {
                        if (fret.length > maxFretLen) maxFretLen = fret.length;
                    });
                });
                const extraWidth = (maxFretLen - 1) * extraDigitWidth;
                const contentWidth = baseWidth + noteCount * widthPerNote + sidePadding * 2 + extraWidth;
                // The final width is the maximum of minSpacingWidth and contentWidth
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
            const renderer = new VF.Renderer(previewDiv, VF.Renderer.Backends.SVG);
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
                    const vfNotes = measure.map((note, i) => {
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
            const svg = previewDiv.querySelector('svg');
            if (svg) {
                const tabNoteGroups = svg.querySelectorAll('g.vf-tabnote');
                tabNoteGroups.forEach(group => {
                    const rects = group.querySelectorAll('rect');
                    rects.forEach(rect => rect.remove());
                });

                // Overlay Unicode rhythm icons above each note
                // Map duration to Unicode icon
                const durationToIcon = {
                    'w': '\uD834\uDD5D', // 𝅝
                    'h': '\uD834\uDD5E', // 𝅗𝅥
                    'q': '\uD834\uDD5F', // 𝅘𝅥
                    '8': '\uD834\uDD60', // 𝅘𝅥𝅮
                    '16': '\uD834\uDD61' // 𝅘𝅥𝅯
                };
                // For each measure, find the corresponding tab notes and their bounding boxes
                let y = 40;
                let noteIdxGlobal = 0;
                lines.forEach(line => {
                    let x = 10;
                    line.forEach(idx => {
                        const measure = measures[idx];
                        const staveWidth = staveWidths[idx];
                        const tabLineY = y + 40; // Top tab line for this measure
                        for (let n = 0; n < measure.length; n++) {
                            const tabNoteGroup = svg.querySelectorAll('g.vf-tabnote')[noteIdxGlobal];
                            if (tabNoteGroup) {
                                const bbox = tabNoteGroup.getBBox();
                                let icon = durationToIcon[measure[n].duration.replace('.', '')] || '';
                                if (measure[n].duration.endsWith('.')) icon += '.';
                                if (icon) {
                                    const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                                    text.setAttribute('x', bbox.x + bbox.width / 2);
                                    text.setAttribute('y', tabLineY - 5); // 5px above the top tab line for this measure
                                    text.setAttribute('fill', '#fff');
                                    text.setAttribute('font-size', '22');
                                    text.setAttribute('text-anchor', 'middle');
                                    text.setAttribute('font-family', 'Bravura, Arial, serif');
                                    text.textContent = icon;
                                    svg.appendChild(text);

                                    // If this note is part of a tuplet, add the tuplet number above the icon
                                    if (measure[n].tuplet) {
                                        const tupletText = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                                        tupletText.setAttribute('x', bbox.x + bbox.width / 2);
                                        tupletText.setAttribute('y', tabLineY - 24); // 13px above the icon
                                        tupletText.setAttribute('fill', '#fff');
                                        tupletText.setAttribute('font-size', '10');
                                        tupletText.setAttribute('text-anchor', 'middle');
                                        tupletText.setAttribute('font-family', 'Arial, serif');
                                        tupletText.textContent = measure[n].tuplet;
                                        svg.appendChild(tupletText);
                                    }
                                }
                            }
                            noteIdxGlobal++;
                        }
                        x += staveWidth;
                    });
                    y += lineHeight; // This ensures y is correct for each line
                });
            }

            messageDiv.textContent = '';
            messageDiv.style.color = '';
        }

        // Initial render
        updateTabNoteList();
        renderVexflowTab();

        // AJAX form submission
        document.getElementById('notation-form').addEventListener('submit', async function(e) {
          e.preventDefault();
          const form = e.target;
          const formData = new FormData(form);
            
            // Check which type of notation we're saving
            const isTabNotation = tabEditor.style.display !== 'none';
            
            if (isTabNotation) {
                // For tab notation, save as JSON
                formData.set('content', JSON.stringify(tabNotes));
            } else {
                // For text notation, save as plain text
                formData.set('content', textContent.value);
            }

          messageDiv.textContent = 'Saving...';
            messageDiv.style.color = '#4faaff';
            
          try {
            const response = await fetch('add_notation.php', {
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
                    messageDiv.textContent = 'Notation added successfully! Redirecting...';
                    
                    // Clear all form data
              form.reset();
                    tabNotes = [];
                    textContent.value = '';
                    
                    // Reset steps
                    step1.style.display = '';
                    step2.style.display = 'none';
                    step3.style.display = 'none';
                    
                    // Clear draft
                    localStorage.removeItem('notationDraft');
                    
                    // Redirect after a short delay
                    setTimeout(() => {
                        window.location.href = 'notations.php';
                    }, 1000);
            } else {
              messageDiv.style.color = '#ff6b6b';
              messageDiv.textContent = result.message || 'Error adding notation.';
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


<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
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
    <title>Add Notation</title>
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
    <div class="wrapper" style="width: 80%; max-width: 1200px; margin: 0 auto;">
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
                <button type="button" id="next-to-tab" class="btn btn-primary">Next</button>
            </div>
            <div id="step2" style="display:none;">
                <div class="form-group" style="width: 100%;">
                    <label style="color: #ccc; margin-bottom: 5px; display: block;">
                        Guitar Tab Editor
                    </label>
                    <div id="tab-editor-controls" style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px;">
                        <label>String:
                            <select id="tab-string" style="background: #2a2a2a; color: #fff; border: 1px solid #464646;">
                                <option value="6">6 (E)</option>
                                <option value="5">5 (A)</option>
                                <option value="4">4 (D)</option>
                                <option value="3">3 (G)</option>
                                <option value="2">2 (B)</option>
                                <option value="1">1 (e)</option>
                            </select>
                        </label>
                        <label>Fret:
                            <input type="number" id="tab-fret" min="0" max="24" value="0" style="width: 50px; background: #2a2a2a; color: #fff; border: 1px solid #464646;">
                        </label>
                        <label>Duration:
                            <select id="tab-duration" style="background: #2a2a2a; color: #fff; border: 1px solid #464646;">
                                <option value="8">Eighth</option>
                                <option value="q">Quarter</option>
                                <option value="h">Half</option>
                                <option value="w">Whole</option>
                            </select>
                        </label>
                        <button type="button" id="add-tab-note" class="btn btn-primary">Add Note</button>
                    </div>
                    <div id="tab-note-list" style="margin-bottom: 10px; color: #ccc; font-size: 0.95rem;"></div>
                    <input type="hidden" name="content" id="tab-json-content">
                    <div id="vf-preview" style="margin-top: 20px; margin-bottom: 20px; background: #181818; border-radius: 4px; padding: 10px;"></div>
                    <button type="button" id="back-to-info" class="btn" style="margin-right: 10px; margin-bottom: 10px;">Back</button>
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
        const nextBtn = document.getElementById('next-to-tab');
        const backBtn = document.getElementById('back-to-info');
        const titleInput = document.getElementById('notation-title');
        const songInput = document.getElementById('notation-songid');
        const instrInput = document.getElementById('notation-instrumentid');
        const messageDiv = document.getElementById('notation-message');

        // --- AUTOSAVE/RESTORE LOGIC ---
        let tabNotes = [];
        const tabString = document.getElementById('tab-string');
        const tabFret = document.getElementById('tab-fret');
        const tabDuration = document.getElementById('tab-duration');
        const addTabNoteBtn = document.getElementById('add-tab-note');
        const tabNoteList = document.getElementById('tab-note-list');
        const tabJsonContent = document.getElementById('tab-json-content');
        const previewDiv = document.getElementById('vf-preview');

        // Restore draft if exists
        let draft = localStorage.getItem('notationDraft');
        if (draft) {
            try {
                draft = JSON.parse(draft);
                if (draft.title) titleInput.value = draft.title;
                if (draft.songid) songInput.value = draft.songid;
                if (draft.instrumentid) instrInput.value = draft.instrumentid;
                if (draft.tabNotes) tabNotes = draft.tabNotes;
                if (draft.step === '2') {
                    step1.style.display = 'none';
                    step2.style.display = '';
                } else {
                    step1.style.display = '';
                    step2.style.display = 'none';
                }
            } catch (e) {
                // If parsing fails, clear the draft
                localStorage.removeItem('notationDraft');
            }
        } else {
            step1.style.display = '';
            step2.style.display = 'none';
        }

        function saveDraft(stepOverride) {
            localStorage.setItem('notationDraft', JSON.stringify({
                title: titleInput.value,
                songid: songInput.value,
                instrumentid: instrInput.value,
                tabNotes: tabNotes,
                step: stepOverride || (step2.style.display === '' ? '2' : '1')
            }));
        }

        // Save on input changes
        titleInput.addEventListener('input', function() { saveDraft(); });
        songInput.addEventListener('change', function() { saveDraft(); });
        instrInput.addEventListener('change', function() { saveDraft(); });

        // --- END AUTOSAVE/RESTORE LOGIC ---

        function updateTabNoteList() {
            tabNoteList.innerHTML = tabNotes.length === 0 ? '<em>No notes yet.</em>' :
                tabNotes.map((note, idx) =>
                    `#${idx+1}: String ${note.str}, Fret ${note.fret}, Duration ${note.duration} <button type='button' data-idx='${idx}' class='remove-tab-note' style='margin-left:8px;color:#ff6b6b;background:none;border:none;cursor:pointer;'>Remove</button>`
                ).join('<br>');
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

        addTabNoteBtn.addEventListener('click', function() {
            const str = parseInt(tabString.value);
            const fret = parseInt(tabFret.value);
            const duration = tabDuration.value;
            if (str >= 1 && str <= 6 && fret >= 0 && fret <= 24) {
                tabNotes.push({str, fret, duration});
                updateTabNoteList();
                renderVexflowTab();
            }
        });

        function renderVexflowTab() {
            previewDiv.innerHTML = '';
            const VF = Vex.Flow;
            const renderer = new VF.Renderer(previewDiv, VF.Renderer.Backends.SVG);
            renderer.resize(700, 220);
            const context = renderer.getContext();
            context.setFont('Arial', 18, '').setFillStyle('#fff');
            const tabStave = new VF.TabStave(10, 40, 650);
            tabStave.addClef('tab').setContext(context).draw();

            const tabLabel = tabStave.getModifiers().find(m => m.getCategory && m.getCategory() === 'clefs');
            if (tabLabel) tabLabel.setStyle({ fillStyle: '#fff', strokeStyle: '#fff' });

            const vfNotes = tabNotes.map(note => {
                const tabNote = new VF.TabNote({positions: [{str: note.str, fret: note.fret}], duration: note.duration});
                tabNote.setStyle({ fillStyle: '#fff', strokeStyle: '#fff' });
                return tabNote;
            });

            const voice = new VF.Voice().setStrict(false);
            voice.addTickables(vfNotes);
            new VF.Formatter().joinVoices([voice]).format([voice], 600);
            voice.draw(context, tabStave);

            // Remove only rectangles that are direct siblings of tab number text
            const svg = previewDiv.querySelector('svg');
            if (svg) {
                const tabNoteGroups = svg.querySelectorAll('g.vf-tabnote');
                tabNoteGroups.forEach(group => {
                    const rects = group.querySelectorAll('rect');
                    rects.forEach(rect => rect.remove());
                });
            }

            messageDiv.textContent = '';
            messageDiv.style.color = '';
        }

        // Initial render
        updateTabNoteList();
        renderVexflowTab();

        nextBtn.addEventListener('click', function() {
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
            saveDraft('2');
        });
        backBtn.addEventListener('click', function() {
            step2.style.display = 'none';
            step1.style.display = '';
            saveDraft('1');
        });

        // AJAX form submission
        document.getElementById('notation-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
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
                    messageDiv.textContent = 'Notation added successfully!';
                    tabNotes = [];
                    updateTabNoteList();
                    renderVexflowTab();
                    localStorage.removeItem('notationDraft'); // Clear draft on success
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
</body>
</html>


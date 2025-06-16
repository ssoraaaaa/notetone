<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

$notation_id = $_GET['id'];

// Fetch the notation details
$notation_sql = "SELECT n.*, n.userid, s.title AS song_title, i.name AS instrument_name, u.username AS user_name, u.moderatorstatus AS user_moderator
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
    <div class="navbar-spacer"></div>

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
        <?php if (!empty($notation['genres'])): ?>
            <p><strong>Genres:</strong> <?php echo htmlspecialchars($notation['genres']); ?></p>
        <?php endif; ?>
        <p><strong>Date Added:</strong> <?php echo htmlspecialchars($notation['dateadded']); ?></p>
        <p><strong>Created by:</strong> <?php 
            $is_admin = isset($notation['user_moderator']) && $notation['user_moderator'] == 1;
            $admin_symbol = $is_admin ? ' <span title="Admin" style="color:#ffcc00;">&#9812;</span>' : '';
            echo $notation['user_name'] ? htmlspecialchars($notation['user_name']) . $admin_symbol : '<em>deleted user</em>'; 
        ?></p>
    </div>
    

    <!-- PDF -->
    <div id="pdf-diary-content">
        <div class="notation-box" style="background: #1a1a1a; border: 1px solid #464646; border-radius: 4px; padding: 18px 20px; margin-bottom: 24px;">
            <?php
            // Show plain text notation in <pre> if not tab JSON
            $is_tab = false;
            try {
                $parsed = json_decode($notation['content'], true);
                $is_tab = is_array($parsed);
            } catch (Exception $e) {
                $is_tab = false;
            }
            if ($is_tab) {
                echo '<div id="vf-container"></div>';
            } else {
                $content = $notation['content'];
                // Convert literal '\n' to real newlines for display
                $content = str_replace('\\n', "\n", $content);
                echo '<pre style="color: #fff; font-family: monospace; white-space: pre-wrap; background: none; border: none; margin: 0; padding: 0;">' . htmlspecialchars($content) . '</pre>';
            }
            ?>
        </div>
    </div>
    <?php if (isLoggedIn() && isset($notation['userid']) && $notation['userid'] == $_SESSION['userid'] && $from === 'mynotations'): ?>
    <button type="button" id="delete-notation-btn" class="btn btn-delete-notation" style="background: #ff6b6b; color: #fff; border: none; box-shadow: none; border-radius: 4px; padding: 10px 22px; font-size: 1rem; cursor: pointer; display:inline-block; margin-right: 10px;">Delete Notation</button>
    <form id="delete-notation-form" method="POST" action="" style="display:none;">
        <input type="hidden" name="delete" value="1">
    </form>
    <a href="edit.php?id=<?php echo $notation['notationid']; ?>&from=mynotations&back=<?php echo urlencode($back_url); ?>" class="btn btn-primary" style="text-decoration: none; padding: 10px 22px; font-size: 1rem; border-radius: 4px; background: #808080; color: #fff; border: none; box-shadow: none; margin-left: 10px;">Edit</a>
    <?php include 'components/notation/delete_modal.php'; ?>
    <?php endif; ?>
    <a href="<?php echo htmlspecialchars($back_url); ?>" class="btn btn-primary" style="text-decoration: none; margin-left: 10px;">&larr; Back</a>

    <div style="text-align: right; margin-bottom: 15px; margin-top: 15px;">
        <button onclick="generatePDF()" style="background: #808080; color: #fff; border: none; box-shadow: none; border-radius: 4px; padding: 10px 22px; font-size: 1rem; cursor: pointer;">Download as PDF</button>
    </div>
</div>
<?php include('includes/footer.php'); ?>
<script src="https://unpkg.com/vexflow/releases/vexflow-min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vexflow@4.2.2/build/cjs/vexflow.js"></script>
<!-- PDF SCRIPT -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
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

    // Split notes into lines at tact lines, with a max notes per line
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

    // Function to render wrapped tab notation
    function renderVexflowTabWrapped(notes) {
        const div = document.getElementById('vf-container');
        div.innerHTML = '';
        const lines = splitNotesIntoLines(notes, 16); // You can adjust max notes per line
        const fixedStaveWidth = 1200;
        const lineHeight = 150;
        const svgHeight = lines.length * lineHeight + 60;
        const renderer = new VF.Renderer(div, VF.Renderer.Backends.SVG);
        renderer.resize(fixedStaveWidth, svgHeight);
        const context = renderer.getContext();
        context.setFont('Arial', 18, '').setFillStyle('#fff');
        let y = 40;
        lines.forEach((lineNotes, idx) => {
            const stave = new VF.TabStave(10, y, fixedStaveWidth);
            if (idx === 0) {
                stave.addClef('tab');
            }
            stave.setContext(context).draw();
            let vfNotes = [];
            lineNotes.forEach(note => {
                if (note.tact) {
                    vfNotes.push(new VF.BarNote());
                } else {
                    let positions = Array.isArray(note.positions) ? note.positions : [{ str: 7 - note.str, fret: note.fret }];
                    vfNotes.push(new VF.TabNote({ positions: positions, duration: 'q' }).setStyle({ fillStyle: '#fff', strokeStyle: '#fff' }));
                }
            });
            if (vfNotes.length > 0) {
                const voice = new VF.Voice().setStrict(false);
                voice.addTickables(vfNotes);
                new VF.Formatter().joinVoices([voice]).format([voice], fixedStaveWidth - 60);
                voice.draw(context, stave);
            }
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

    // Initialize the notation display
    if (isTabNotation(notationContent)) {
        renderVexflowTabWrapped(JSON.parse(notationContent));
    }
    // else: do nothing, PHP already rendered the plain text
</script>
<script>
async function generatePDF() {
    if (!window.jspdf || !window.jspdf.jsPDF) {
        alert("jsPDF not loaded");
        return;
    }

    const { jsPDF } = window.jspdf;
    const diaryElement = document.getElementById("pdf-diary-content");
    const nickname = "<?= $_SESSION['username'] ?? 'User' ?>";
    const notationTitle = "<?php echo addslashes($notation['title']); ?>";

    const now = new Date();
    const date = String(now.getDate()).padStart(2, '0') + '.' + String(now.getMonth() + 1).padStart(2, '0') + '.' + now.getFullYear();

    if (!diaryElement) {
        alert("Notation content not found");
        return;
    }

    // Elements to temporarily hide
    const downloadButton = diaryElement.querySelector("button");
    const shadows = diaryElement.querySelectorAll(".shadow");

    // Hide button and remove shadows before rendering
    if (downloadButton) downloadButton.style.display = "none";
    shadows.forEach(el => el.classList.remove("shadow"));

    // Temporarily change colors for PDF (black on white)
    const notationBox = diaryElement.querySelector(".notation-box");
    const svg = diaryElement.querySelector("svg");
    const pre = notationBox ? notationBox.querySelector("pre") : null;
    const originalNotationBg = notationBox ? notationBox.style.background : null;
    const originalPreColor = pre ? pre.style.color : null;
    const originalPreBg = pre ? pre.style.background : null;
    const originalElements = [];
    
    if (notationBox) {
        notationBox.style.background = "white";
        notationBox.style.border = "1px solid #000";
    }
    if (pre) {
        pre.style.color = "black";
        pre.style.background = "white";
    }
    
    if (svg) {
        // Change all white text and strokes to black
        const whiteElements = svg.querySelectorAll('[fill="#fff"], [stroke="#fff"], [fill="white"], [stroke="white"]');
        whiteElements.forEach(el => {
            originalElements.push({
                element: el,
                originalFill: el.getAttribute('fill'),
                originalStroke: el.getAttribute('stroke')
            });
            if (el.getAttribute('fill') === '#fff' || el.getAttribute('fill') === 'white') {
                el.setAttribute('fill', 'black');
            }
            if (el.getAttribute('stroke') === '#fff' || el.getAttribute('stroke') === 'white') {
                el.setAttribute('stroke', 'black');
            }
        });
        
        // Change tab staff lines to black
        const lines = svg.querySelectorAll('line');
        lines.forEach(line => {
            const stroke = line.getAttribute('stroke');
            if (stroke === '#fff' || stroke === 'white') {
                originalElements.push({
                    element: line,
                    originalStroke: stroke
                });
                line.setAttribute('stroke', 'black');
            }
        });
    }

    // Wait for rendering without these elements
    const canvas = await html2canvas(diaryElement, { scale: 2, backgroundColor: 'white' });

    // Restore original colors
    if (notationBox && originalNotationBg) {
        notationBox.style.background = originalNotationBg;
        notationBox.style.border = "1px solid #464646";
    }
    if (pre) {
        pre.style.color = originalPreColor;
        pre.style.background = originalPreBg;
    }
    
    originalElements.forEach(item => {
        if (item.originalFill) {
            item.element.setAttribute('fill', item.originalFill);
        }
        if (item.originalStroke) {
            item.element.setAttribute('stroke', item.originalStroke);
        }
    });

    // Restore button and shadows
    if (downloadButton) downloadButton.style.display = "inline-block";
    shadows.forEach(el => el.classList.add("shadow"));

    // Generate PDF
    const pdf = new jsPDF('p', 'mm', 'a4');
    
    // Try to load logo (optional)
    try {
        const img = new Image();
        img.src = 'assets/images/logo-white.png';
        await img.decode();

        const logoWidth = 35;
        const aspectRatio = img.height / img.width;
        const logoHeight = logoWidth * aspectRatio;
        const pageWidth = pdf.internal.pageSize.getWidth();
        // Center the logo horizontally
        const logoX = (pageWidth - logoWidth) / 2;
        pdf.addImage(img, 'PNG', logoX, 10, logoWidth, logoHeight);
    } catch (e) {
        console.log('Logo not loaded, continuing without it');
    }

    const imgData = canvas.toDataURL('image/png');
    const pageWidth = pdf.internal.pageSize.getWidth();
    const imgWidth = pageWidth - 20;
    const imgHeight = imgWidth * (canvas.height / canvas.width);

    // Title
    pdf.setFontSize(16);
    pdf.setFont("helvetica", "bold");
    pdf.text(notationTitle, 105, 30, { align: "center" });

    // Date
    pdf.setFontSize(10);
    pdf.setFont("helvetica", "normal");
    pdf.text(date, 105, 36, { align: "center" });
    pdf.text("Freshly made for " + nickname, 105, 42, { align: "center" });

    // Content
    pdf.addImage(imgData, 'PNG', 10, 45, imgWidth, imgHeight);

    pdf.save('notation_' + notationTitle.replace(/[^a-z0-9]/gi, '_') + '.pdf');
}
</script>

</body>
</html>

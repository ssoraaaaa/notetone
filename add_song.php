<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Fetch genres for selection
$genres = [];
$genre_result = $conn->query("SELECT * FROM genres ORDER BY name ASC");
if ($genre_result && $genre_result->num_rows > 0) {
    while ($row = $genre_result->fetch_assoc()) {
        $genres[] = $row;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $performer = trim($_POST['performer'] ?? '');
    $selected_genres = $_POST['genres'] ?? [];
    $userid = $_SESSION['userid'];
    $errors = [];

    if ($title === '' || $performer === '') {
        $errors[] = 'Title and performer are required.';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare('INSERT INTO songs (title, performer, userid, status) VALUES (?, ?, ?, "pending")');
        $stmt->bind_param('ssi', $title, $performer, $userid);
        if ($stmt->execute()) {
            $songid = $conn->insert_id;
            // Insert genres
            if (!empty($selected_genres)) {
                $sg_stmt = $conn->prepare('INSERT INTO song_genres (songid, genreid) VALUES (?, ?)');
                foreach ($selected_genres as $genreid) {
                    $sg_stmt->bind_param('ii', $songid, $genreid);
                    $sg_stmt->execute();
                }
            }
            $success = true;
            $pending_message = 'Your song has been submitted and is pending admin approval.';
        } else {
            $errors[] = 'Database error: ' . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Song</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="navbar-spacer"></div>
<div class="container">
    <div class="wrapper" style="max-width: 600px; margin: 40px auto; background: #232323; border-radius: 8px; padding: 32px 24px; box-shadow: 0 2px 16px #0002;">
        <a href="dashboard.php" class="btn btn-primary" style="margin-bottom: 18px; display: inline-block;">&larr; Back</a>
        <h2 style="color: #fff;">Add Song</h2>
        <?php if (!empty($errors)): ?>
            <div style="color: #ff6b6b; margin-bottom: 16px;">
                <?php foreach ($errors as $err) echo '<div>' . htmlspecialchars($err) . '</div>'; ?>
            </div>
        <?php elseif (!empty($success)): ?>
            <div style="color: #4caf50; margin-bottom: 16px;">Song added successfully!</div>
        <?php endif; ?>
        <?php if (!empty($pending_message)): ?>
            <div style="color: #ffcc00; margin-bottom: 16px;"> <?php echo $pending_message; ?> </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group" style="margin-bottom: 18px;">
                <label for="title"  style="color: #fff;">Title</label>
                <input type="text" placeholder="Enter the song's title" name="title" id="title" class="form-control" required style="width: 100%; background: #2a2a2a; color: #fff; border: 1px solid #464646;">
            </div>
            <div class="form-group" style="margin-bottom: 18px;">
                <label for="performer"  style="color: #fff;">Performer</label>
                <input type="text" placeholder="Enter the creator of the song" name="performer" id="performer" class="form-control" required style="width: 100%; background: #2a2a2a; color: #fff; border: 1px solid #464646;">
            </div>
            <div class="form-group" style="margin-bottom: 18px; position: relative;">
                <label for="genres" style="color: #fff;">Genres</label>
                <div id="genre-dropdown-container" style="position: relative; width: 100%;">
                    <input type="text" id="genre-search" placeholder="Type to search genres..." autocomplete="off" style="width: 100%; background: #2a2a2a; color: #fff; border: 1px solid #464646; padding: 8px; border-radius: 4px; margin-bottom: 8px; box-sizing: border-box;">
                    <div id="genre-dropdown" style="max-height: 150px; overflow-y: auto; background: #232323; border: 1px solid #464646; border-radius: 4px; display: none; position: absolute; z-index: 10; width: 100%; left: 0; top: 40px; box-sizing: border-box;"></div>
                    <div id="selected-genres" style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px;"></div>
                </div>
                <input type="hidden" name="genres[]" id="genres-hidden">
                <small style="color: #888;">Type to filter, press Enter or click to select. Click a genre chip to remove.</small>
            </div>
            <button type="submit" class="btn btn-primary">Add Song</button>
        </form>
    </div>
</div>
<script>
const genres = <?php echo json_encode($genres); ?>;
let selectedGenres = [];

const genreSearch = document.getElementById('genre-search');
const genreDropdown = document.getElementById('genre-dropdown');
const selectedGenresDiv = document.getElementById('selected-genres');
const genresHidden = document.getElementById('genres-hidden');
const container = document.getElementById('genre-dropdown-container');

function updateDropdown() {
    const search = genreSearch.value.toLowerCase();
    genreDropdown.innerHTML = '';
    let filtered = genres.filter(g =>
        g.name.toLowerCase().includes(search) && !selectedGenres.includes(g.genreid)
    );
    if (filtered.length === 0) {
        genreDropdown.style.display = 'none';
        return;
    }
    filtered.forEach(g => {
        const div = document.createElement('div');
        div.textContent = g.name;
        div.style.padding = '8px 12px';
        div.style.cursor = 'pointer';
        div.style.borderBottom = '1px solid #333';
        div.addEventListener('mousedown', e => {
            e.preventDefault();
            selectGenre(g.genreid);
        });
        genreDropdown.appendChild(div);
    });
    genreDropdown.style.display = 'block';
}

function selectGenre(id) {
    if (!selectedGenres.includes(id)) {
        selectedGenres.push(id);
        updateSelectedGenres();
        genreSearch.value = '';
        genreDropdown.style.display = 'none';
        updateDropdown();
    }
}

function updateSelectedGenres() {
    selectedGenresDiv.innerHTML = '';
    genresHidden.value = selectedGenres.join(',');
    selectedGenres.forEach(id => {
        const genre = genres.find(g => g.genreid == id);
        if (!genre) return;
        const chip = document.createElement('div');
        chip.textContent = genre.name;
        chip.style.display = 'inline-flex';
        chip.style.alignItems = 'center';
        chip.style.background = '#181818';
        chip.style.border = '1px solid #4faaff';
        chip.style.color = '#fff';
        chip.style.borderRadius = '20px';
        chip.style.padding = '6px 14px 6px 14px';
        chip.style.fontSize = '0.95rem';
        chip.style.cursor = 'pointer';
        chip.style.position = 'relative';
        chip.style.transition = 'background 0.2s';
        chip.addEventListener('click', () => removeGenre(id));
        // X icon
        const x = document.createElement('span');
        x.textContent = '✕';
        x.style.marginLeft = '10px';
        x.style.fontWeight = 'bold';
        x.style.color = '#4faaff';
        x.style.fontSize = '1rem';
        chip.appendChild(x);
        selectedGenresDiv.appendChild(chip);
    });
}

function removeGenre(id) {
    selectedGenres = selectedGenres.filter(gid => gid !== id);
    updateSelectedGenres();
    updateDropdown();
}

genreSearch.addEventListener('input', updateDropdown);
genreSearch.addEventListener('focus', updateDropdown);
genreSearch.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const search = genreSearch.value.toLowerCase();
        const match = genres.find(g => g.name.toLowerCase().startsWith(search) && !selectedGenres.includes(g.genreid));
        if (match) {
            selectGenre(match.genreid);
        }
    }
});
document.addEventListener('click', function(e) {
    if (!container.contains(e.target)) {
        genreDropdown.style.display = 'none';
    }
});
// On submit, set hidden input as array
const form = document.querySelector('form');
form.addEventListener('submit', function(e) {
    // Convert comma string to array for PHP
    let arr = selectedGenres.map(String);
    genresHidden.name = 'genres[]';
    genresHidden.value = arr.join(',');
});
</script>
</body>
</html> 
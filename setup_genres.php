<?php
require_once 'includes/db.php';

// Create genres table
$create_genres = "CREATE TABLE IF NOT EXISTS `genres` (
    `genreid` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    `description` text DEFAULT NULL,
    PRIMARY KEY (`genreid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if ($conn->query($create_genres)) {
    echo "Genres table created successfully<br>";
} else {
    echo "Error creating genres table: " . $conn->error . "<br>";
}

// Create song_genres table
$create_song_genres = "CREATE TABLE IF NOT EXISTS `song_genres` (
    `songid` int(11) NOT NULL,
    `genreid` int(11) NOT NULL,
    PRIMARY KEY (`songid`, `genreid`),
    KEY `genreid` (`genreid`),
    CONSTRAINT `song_genres_ibfk_1` FOREIGN KEY (`songid`) REFERENCES `songs` (`songid`) ON DELETE CASCADE,
    CONSTRAINT `song_genres_ibfk_2` FOREIGN KEY (`genreid`) REFERENCES `genres` (`genreid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if ($conn->query($create_song_genres)) {
    echo "Song_genres table created successfully<br>";
} else {
    echo "Error creating song_genres table: " . $conn->error . "<br>";
}

// Insert initial genre data
$genres = [
    ['Rock', 'Rock music and its subgenres'],
    ['Pop', 'Popular music'],
    ['Jazz', 'Jazz music and its variations'],
    ['Classical', 'Classical music'],
    ['Electronic', 'Electronic and dance music'],
    ['Hip Hop', 'Hip hop and rap music'],
    ['Metal', 'Heavy metal and its subgenres'],
    ['Folk', 'Folk music'],
    ['Blues', 'Blues music'],
    ['Country', 'Country music']
];

$insert_genre = $conn->prepare("INSERT IGNORE INTO genres (name, description) VALUES (?, ?)");
foreach ($genres as $genre) {
    $insert_genre->bind_param("ss", $genre[0], $genre[1]);
    if ($insert_genre->execute()) {
        echo "Added genre: " . $genre[0] . "<br>";
    } else {
        echo "Error adding genre " . $genre[0] . ": " . $conn->error . "<br>";
    }
}

echo "<br>Setup complete! <a href='notations.php'>Return to notations</a>";
?> 
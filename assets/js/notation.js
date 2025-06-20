document.addEventListener('DOMContentLoaded', function() {
    // Initialize OpenSheetMusicDisplay if the container exists
    const osmdContainer = document.getElementById('osmd-container');
    if (osmdContainer) {
        const musicXML = document.getElementById('musicxml-source').textContent;
        if (musicXML.trim() !== "") {
            const osmd = new opensheetmusicdisplay.OpenSheetMusicDisplay("osmd-container");
            osmd.load(musicXML).then(function() {
                osmd.render();
            }).catch(function(e) {
                osmdContainer.innerHTML = "<p style='color:red;'>Error rendering MusicXML: " + e.message + "</p>";
            });
        } else {
            osmdContainer.innerHTML = "<p>No MusicXML content available.</p>";
        }
    }

    // Handle notation deletion
    // (Removed default confirm logic; handled by custom modal in notation.php)
}); 
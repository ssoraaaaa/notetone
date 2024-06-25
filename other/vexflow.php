<script>
        // VexFlow code to render notation
        const VF = Vex.Flow;

        // Create an SVG renderer and attach it to the DIV element named "notation-container".
        const div = document.getElementById("notation-container");
        const renderer = new VF.Renderer(div, VF.Renderer.Backends.SVG);

        // Configure the rendering context.
        renderer.resize(500, 200);
        const context = renderer.getContext();
        context.setFont("Arial", 10, "").setBackgroundFillStyle("#eed");

        // Create a stave of width 400 at position 10, 40 on the canvas.
        const stave = new VF.Stave(10, 40, 400);

        // Add a clef and time signature.
        stave.addClef("treble").addTimeSignature("4/4");

        // Connect it to the rendering context and draw!
        stave.setContext(context).draw();

        // Create the notes
        const notes = [
          new VF.StaveNote({clef: "treble", keys: ["c/4"], duration: "q"}),
          new VF.StaveNote({clef: "treble", keys: ["d/4"], duration: "q"}),
          new VF.StaveNote({clef: "treble", keys: ["e/4"], duration: "q"}),
          new VF.StaveNote({clef: "treble", keys: ["f/4"], duration: "q"})
        ];

        // Create a voice in 4/4 and add above notes
        const voice = new VF.Voice({num_beats: 4,  beat_value: 4});
        voice.addTickables(notes);

        // Format and justify the notes to 400 pixels.
        const formatter = new VF.Formatter().joinVoices([voice]).format([voice], 400);

        // Render voice
        voice.draw(context, stave);
    </script>
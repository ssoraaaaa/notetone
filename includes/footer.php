    <?php if (isset($additional_js)): ?>
        <?php foreach ($additional_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('input', function(e) {
        if (e.target.tagName.toLowerCase() === 'textarea') {
            console.log('textarea input detected');
            e.target.style.height = 'auto';
            e.target.style.height = e.target.scrollHeight + 'px';
            e.target.style.overflowY = 'hidden';
        }
    });
    </script>
</body>
</html> 
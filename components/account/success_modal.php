<?php /* Account Deletion Success Modal Component */ ?>
<div id="success-modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.45); align-items:center; justify-content:center;">
    <div style="background:#232323; color:#fff; border-radius:8px; padding:32px 28px; box-shadow:0 2px 16px #0006; min-width:320px; max-width:90vw; text-align:center;">
        <h2 style="margin-bottom:18px; font-size:1.3rem; color:#4CAF50;">Account Deleted Successfully</h2>
        <p style="margin-bottom:24px; color:#ffffff;">Your account has been successfully deleted.</p>
        <button type="button" id="success-modal-close" style="background:#4CAF50; color:#fff; border:none; border-radius:4px; padding:10px 24px; font-size:1rem; cursor:pointer;">Close</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if we should show the success modal
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('deleted') === 'true') {
        const modal = document.getElementById('success-modal');
        const closeBtn = document.getElementById('success-modal-close');
        
        modal.style.display = 'flex';
        
        // Close modal when clicking the close button
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
            // Remove the query parameter from the URL
            window.history.replaceState({}, document.title, window.location.pathname);
        });
        
        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
                // Remove the query parameter from the URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });
    }
});
</script> 
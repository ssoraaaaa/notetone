<?php /* Delete Confirmation Modal Component */ ?>
<div id="delete-modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.45); align-items:center; justify-content:center;">
    <div style="background:#232323; color:#fff; border-radius:8px; padding:32px 28px; box-shadow:0 2px 16px #0006; min-width:320px; max-width:90vw; text-align:center;">
        <h3 style="margin-bottom:18px; font-size:1.3rem;">Delete Thread?</h3>
        <p style="margin-bottom:24px; color:#ffb3b3;">Are you sure you want to delete this thread? This cannot be undone.</p>
        <div style="display:flex; gap:18px; justify-content:center;">
            <button id="modal-cancel-btn" style="background:#444; color:#fff; border:none; border-radius:4px; padding:10px 24px; font-size:1rem; cursor:pointer;">Cancel</button>
            <button id="modal-confirm-btn" style="background:#ff6b6b; color:#fff; border:none; border-radius:4px; padding:10px 24px; font-size:1rem; cursor:pointer;">Delete</button>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var deleteBtn = document.getElementById('delete-thread-btn');
    var modal = document.getElementById('delete-modal');
    var cancelBtn = document.getElementById('modal-cancel-btn');
    var confirmBtn = document.getElementById('modal-confirm-btn');
    if (deleteBtn && modal && cancelBtn && confirmBtn) {
        deleteBtn.addEventListener('click', function() {
            modal.style.display = 'flex';
        });
        cancelBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
        confirmBtn.addEventListener('click', function() {
            document.getElementById('delete-thread-form').submit();
        });
        // Optional: close modal on background click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) modal.style.display = 'none';
        });
    }
});
</script> 
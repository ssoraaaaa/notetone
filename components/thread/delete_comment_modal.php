<?php /* Delete Comment Confirmation Modal Component */ ?>
<div id="delete-comment-modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.45); align-items:center; justify-content:center;">
    <div style="background:#232323; color:#fff; border-radius:8px; padding:32px 28px; box-shadow:0 2px 16px #0006; min-width:320px; max-width:90vw; text-align:center;">
        <h3 style="margin-bottom:18px; font-size:1.3rem;">Delete Comment?</h3>
        <p style="margin-bottom:24px; color:#ffb3b3;">Are you sure you want to delete this comment?</p>
        <div style="display:flex; gap:18px; justify-content:center;">
            <button id="comment-modal-cancel-btn" style="background:#444; color:#fff; border:none; border-radius:4px; padding:10px 24px; font-size:1rem; cursor:pointer;">Cancel</button>
            <button id="comment-modal-confirm-btn" style="background:#ff6b6b; color:#fff; border:none; border-radius:4px; padding:10px 24px; font-size:1rem; cursor:pointer;">Delete</button>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle all delete comment buttons
    document.querySelectorAll('.delete-comment-btn').forEach(function(deleteBtn) {
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            var modal = document.getElementById('delete-comment-modal');
            modal.dataset.formToSubmit = this.closest('form').id;
            modal.dataset.commentElement = this.closest('.comment-box').id;
            modal.style.display = 'flex';
        });
    });

    // Handle modal buttons
    var modal = document.getElementById('delete-comment-modal');
    var cancelBtn = document.getElementById('comment-modal-cancel-btn');
    var confirmBtn = document.getElementById('comment-modal-confirm-btn');

    if (modal && cancelBtn && confirmBtn) {
        cancelBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        confirmBtn.addEventListener('click', function() {
            var formId = modal.dataset.formToSubmit;
            var commentElementId = modal.dataset.commentElement;
            
            if (formId) {
                var form = document.getElementById(formId);
                var formData = new FormData(form);
                
                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(() => {
                    // Remove the comment element from the DOM
                    var commentElement = document.getElementById(commentElementId);
                    if (commentElement) {
                        commentElement.remove();
                    }
                    modal.style.display = 'none';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting comment. Please try again.');
                });
            }
        });

        // Close modal on background click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) modal.style.display = 'none';
        });
    }
});
</script> 
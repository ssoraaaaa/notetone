<?php /* Delete Account Confirmation Modal Component */ ?>
<div id="delete-account-modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.45); align-items:center; justify-content:center;">
    <div id="modal-content" style="background:#232323; color:#fff; border-radius:8px; padding:40px 40px; box-shadow:0 2px 16px #0006; min-width:400px; max-width:95vw; text-align:center; transition: all 0.3s ease;">
        <h2 style="margin-bottom:18px; font-size:1.3rem; transition: color 0.3s ease;">Delete Account?</h2>
        <p style="margin-bottom:24px; color:#ffffff; transition: color 0.3s ease;">Note that your notations will not be deleted.</p>
        <p style="margin-bottom:24px; color:#ffffff; transition: color 0.3s ease;">This action cannot be undone.</p>
        <div id="easter-egg-message" style="margin-bottom:24px; color:#AAffAA; display: none; transition: color 0.3s ease;">You're going to delete your account?</div>
        <div id="delete-account-error" style="color: #ff6b6b; margin-bottom: 20px; display: none;"></div>
        <form id="delete-account-form" method="POST" action="">
            <div style="margin-bottom: 20px;">
                <input type="password" id="delete-account-password" placeholder="Enter your password" style="width: 100%; padding: 10px; background: #2a2a2a; border: 1px solid #464646; color: #fff; border-radius: 4px; font-size: 1rem; transition: all 0.3s ease;">
            </div>
            <div style="display:flex; gap:18px; justify-content:center;">
                <button type="button" id="account-modal-cancel-btn" style="background:#444; color:#fff; border:none; border-radius:4px; padding:10px 24px; font-size:1rem; cursor:pointer; transition: all 0.3s ease;">Cancel</button>
                <button type="button" id="account-modal-confirm-btn" style="background:#ff4141; color:#fff; border:none; border-radius:4px; padding:10px 24px; font-size:1rem; cursor:pointer; transition: all 0.3s ease;">Delete</button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var deleteBtn = document.getElementById('delete-profile-btn');
    var modal = document.getElementById('delete-account-modal');
    var modalContent = document.getElementById('modal-content');
    var cancelBtn = document.getElementById('account-modal-cancel-btn');
    var confirmBtn = document.getElementById('account-modal-confirm-btn');
    var errorDiv = document.getElementById('delete-account-error');
    var passwordInput = document.getElementById('delete-account-password');
    var easterEggMessage = document.getElementById('easter-egg-message');
    
    if (deleteBtn && modal && cancelBtn && confirmBtn) {
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            errorDiv.style.display = 'none';
            passwordInput.value = '';
            
            // Check for easter egg (1% chance)
            if (Math.random() < 0.01) {
                easterEggMessage.style.display = 'block';
            } else {
                easterEggMessage.style.display = 'none';
            }
            
            modal.style.display = 'flex';
        });

        cancelBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        confirmBtn.addEventListener('click', function() {
            var password = passwordInput.value;
            
            if (!password) {
                errorDiv.textContent = 'Please enter your password';
                errorDiv.style.display = 'block';
                return;
            }

            // Disable the buttons while processing
            confirmBtn.disabled = true;
            cancelBtn.disabled = true;
            errorDiv.style.display = 'none';
            
            console.log('Sending delete account request...');
            
            fetch('verify_delete_account.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'password=' + encodeURIComponent(password)
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Failed to parse JSON:', text);
                        throw new Error('Invalid JSON response');
                    }
                });
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    console.log('Account deletion successful, redirecting...');
                    window.location.href = data.redirect || 'index.php?deleted=true';
                } else {
                    console.error('Error from server:', data.message);
                    errorDiv.textContent = data.message || 'An error occurred. Please try again.';
                    errorDiv.style.display = 'block';
                    // Re-enable the buttons
                    confirmBtn.disabled = false;
                    cancelBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorDiv.textContent = 'An error occurred. Please try again.';
                errorDiv.style.display = 'block';
                // Re-enable the buttons
                confirmBtn.disabled = false;
                cancelBtn.disabled = false;
            });
        });

        // Close modal on background click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Add hover effects
        confirmBtn.addEventListener('mouseenter', function() {
            modalContent.style.background = '#2a1a1a';
            modalContent.style.boxShadow = '0 2px 16px #ff000033';
            modalContent.querySelectorAll('p, h2').forEach(el => {
                el.style.color = '#ffb3b3';
            });
            passwordInput.style.background = '#1a0a0a';
            passwordInput.style.borderColor = '#ff4141';
            cancelBtn.style.background = '#ff4141';
        });

        confirmBtn.addEventListener('mouseleave', function() {
            modalContent.style.background = '#232323';
            modalContent.style.boxShadow = '0 2px 16px #0006';
            modalContent.querySelectorAll('p, h2').forEach(el => {
                el.style.color = '#ffffff';
            });
            passwordInput.style.background = '#2a2a2a';
            passwordInput.style.borderColor = '#464646';
            cancelBtn.style.background = '#444';
        });
    }
});
</script>


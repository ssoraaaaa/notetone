<?php
require_once 'includes/session.php';
?>
<link rel="stylesheet" href="includes/navbar.css">
<nav class="navbar">
    <a href="<?php echo isLoggedIn() ? 'dashboard.php' : 'index.php'; ?>" class="navbar-logo-container">
        <img src="assets/images/logo-gray.png" class="navbar-logo" alt="Logo">
    </a>
    <ul class="navbar-items">
        <?php if (isLoggedIn()): ?>
            <li class="navbar-item">
                <a class="navbar-link" href="dashboard.php">Dashboard</a>
            </li>
            <li class="navbar-item">
                <a class="navbar-link" href="threads.php">Threads</a>
            </li>
            <li class="navbar-item">
                <a class="navbar-link" href="notations.php">Notations</a>
            </li>
            <li class="navbar-item">
                <a class="navbar-link" href="mythreads.php">My Threads</a>
            </li>
            <li class="navbar-item">
                <a class="navbar-link" href="mynotations.php">My Notations</a>
            </li>
            <li class="navbar-item">
                <a class="navbar-link" href="profile.php">Profile</a>
            </li>
        <?php else: ?>
            <li class="navbar-item">
                <a class="navbar-link" href="threads.php">Threads</a>
            </li>
            <li class="navbar-item">
                <a class="navbar-link" href="notations.php">Notations</a>
            </li>
        <?php endif; ?>
    </ul>
    <div class="navbar-auth">
        <?php if (isLoggedIn()): ?>
            <a class="navbar-link" href="logout.php">Logout</a>
        <?php else: ?>
            <a class="navbar-link" href="login.php">Log in</a>
            <a class="navbar-link" href="register.php">Register</a>
        <?php endif; ?>
    </div>
</nav>
<div class="navbar-spacer"></div> 
<?php
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>
<ul class="header">
    <a href="dashboard.php"><img src="assets/images/logo-gray.png" class="header_logo" alt="Logo"></a>
    <li class="li_header"><a class="a_header" href="dashboard.php">Dashboard</a></li>
    <li class="li_header"><a class="a_header" href="threads.php">My Threads</a></li>
    <li class="li_header"><a class="a_header" href="notations.php">My Notations</a></li>
    <li class="li_header"><a class="a_header" href="profile.php">Profile</a></li>
    <li class="li_header"><a class="a_header" href="logout.php">Logout</a></li>
</ul> 
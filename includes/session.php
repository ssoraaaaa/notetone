<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['username']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function getUsername() {
    return isLoggedIn() ? $_SESSION['username'] : 'Guest';
}
?> 
<?php
// functions/logout.php

session_start(); // Ensure the session is started

/**
 * Logout the current user by destroying the session
 */
function logoutUser() {
    // Unset all session variables
    $_SESSION = [];

    // Destroy the session
    session_destroy();

    // Redirect to login page (or homepage)
    header('Location: ../login.php');
    exit;
}

logoutUser();
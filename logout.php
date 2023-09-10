<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to a confirmation page or the login page
    header("Location: signin.php"); // You can change this to the desired page
    exit();
} else {
    // If the user is not logged in, redirect to the login page
    header("Location: signin.php"); // Replace with your login page URL
    exit();
}
?>

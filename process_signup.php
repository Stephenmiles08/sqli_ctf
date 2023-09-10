<?php
session_start();
require "dbcredentials.php";

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email already exists
    $checkEmailQuery = "SELECT COUNT(*) AS emailCount FROM users WHERE email = ?";
    $preparedCheckQuery = $connect_db->prepare($checkEmailQuery);
    $preparedCheckQuery->bind_param('s', $email);
    $preparedCheckQuery->execute();
    $result = $preparedCheckQuery->get_result();
    $emailCount = $result->fetch_assoc()['emailCount'];
    $preparedCheckQuery->close();

    if ($emailCount > 0) {
        // Email already exists, show an error message
        $_SESSION['message'] = "This email is already registered.";
        header("Location: signup.php");
        exit; // Exit to prevent further execution
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $role = 'user';

    // Insert the hashed password into the database
    $insertQuery = "INSERT INTO users (`username`, `email`, `role`, `password`) VALUES (?, ?, ?, ?)";
    $preparedInsertQuery = $connect_db->prepare($insertQuery);
    $preparedInsertQuery->bind_param("ssss", $username, $email, $role, $hashedPassword);

    if ($preparedInsertQuery->execute()) {
        $_SESSION['message'] = "Registration Successful";
        header("Location: signin.php");
    } else {
        $_SESSION['message'] = "Registration Failed. Try again later.";
        header("Location: signup.php");
    }

    // Close the prepared statement for insertion
    $preparedInsertQuery->close();
} else {
    header("Location: signup.php");
}
?>

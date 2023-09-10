<?php
require 'vendor/autoload.php';

// Load environment variables from .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$localhost = 'localhost';
$username = 'root';
$password = $_ENV['db_pass'];
$db_name = $_ENV['db_user'];
$connect_db = new mysqli($localhost, $username, $password, $db_name);

// Check for a successful database connection
if ($connect_db->connect_error) {
    die('Unable to connect: ' . $connect_db->connect_error);
} else {
    // Check if the "users" table exists; if not, create it
    $checkTableQuery = "CREATE TABLE IF NOT EXISTS resetpasswords (
        reset_id INT AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL, used VARCHAR(255) NOT NULL,
        date DATETIME NOT NULL
    )";

    if ($connect_db->query($checkTableQuery) == TRUE) {
        echo "<script>console.log('Table resetpasswords created successfully or already exists.')</script>";
    } else {
        echo "Error creating table: " . $connect_db->error;
    }

    $checkUsersTableQuery = "SHOW TABLES LIKE 'users'";
    $usersTableResult = $connect_db->query($checkUsersTableQuery);

    if ($usersTableResult->num_rows == 0) {
        // The users table does not exist; create it
        $createUsersTableQuery = "CREATE TABLE users (
            user_id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) UNIQUE,
            username VARCHAR(255),
            role VARCHAR(255),
            password VARCHAR(555)
        )";
        if ($connect_db->query($createUsersTableQuery) === TRUE) {
            echo "users table created successfully.<br>";
        } else {
            echo "Error creating users table: " . $connect_db->error . "<br>";
        }
    }

    // Check if a user with the "admin" role already exists in the users table
    $checkAdminUserQuery = "SELECT COUNT(*) AS adminCount FROM users WHERE role = 'admin'";
    $adminUserResult = $connect_db->query($checkAdminUserQuery);

    if ($adminUserResult && $adminUserResult->num_rows > 0) {
        $adminUserRow = $adminUserResult->fetch_assoc();
        $adminUserCount = $adminUserRow['adminCount'];

        if ($adminUserCount == 0) {
            // Insert the "admin" user if it doesn't already exist
            $username = 'admin';
            $email = 'admin@gmail.com';
            $role = 'admin';
            $password = '$2y$10$z4knpliJlHAXg2B2KS49Iuu0.2YTw9piGx/zNP6/0uG69EY/j2mFK'; // Hashed password

            $insertUserQuery = "INSERT INTO users (username, email, role, password) VALUES (?, ?, ?, ?)";
            $preparedQuery = $connect_db->prepare($insertUserQuery);

            if ($preparedQuery) {
                $preparedQuery->bind_param("ssss", $username, $email, $role, $password);
                if ($preparedQuery->execute()) {
                    echo "User inserted successfully.";
                } else {
                    echo "Error inserting user: " . $preparedQuery->error;
                }
                $preparedQuery->close();
            } else {
                echo "Error preparing query: " . $connect_db->error;
            }
        } else {
            // echo "Admin user with the 'admin' role already exists in the users table.<br>";
        }
    }
}
?>

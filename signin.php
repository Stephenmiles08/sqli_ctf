<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        body {
            background-color: #f5f5f5;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            max-width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .login-title {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
        }

        .alert {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-box">
            <?php
            session_start();
            ini_set('display_errors', 1);
            ini_set('error_reporting', E_ALL);
            
            require "dbcredentials.php";
            
            if (isset($_POST['submit'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];

                // Use prepared statements to prevent SQL injection
                $query = "SELECT * FROM users WHERE username = ?";
                $preparedQuery = $connect_db->prepare($query);
                $preparedQuery->bind_param('s', $username);
                $exec = $preparedQuery->execute();
                $result = $preparedQuery->get_result();

                if ($result->num_rows > 0) {
                    $userDetails = $result->fetch_assoc();
                    $storedPassword = $userDetails['password'];

                    // Verify the password using password_verify
                    if (password_verify($password, $storedPassword)) {
                        $_SESSION["username"] = $userDetails['username'];
                        $_SESSION["role"] = $userDetails['role'];
                        if ($userDetails['role'] == 'admin')
                        {
                            header('Location: admin.php');
                        } else{
                            header("Location: dashboard.php");
                        }
                        exit; // Ensure script stops executing after redirection
                    } else {
                        echo "<div class='alert alert-danger'>Incorrect Password.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Invalid Login Details.</div>";
                }
            }
            ?>
            <h1 class="login-title">Sign In</h1>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <input type="text" name="username" placeholder="Username" class="form-control mb-3" required>
                <input type="password" name="password" placeholder="Password" class="form-control mb-3" required>
                <input class="btn btn-success w-100" name="submit" type="submit" value="Submit">
                <p class="text">Forgot Password? <a href="forgotPassword.php">Reset it here</a></p>
            </form>
        </div>
    </div>
</body>
</html>

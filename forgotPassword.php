<?php
require "dbcredentials.php";
$result = '';
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

if (isset($_POST['submit'])) {
    $userInfo = trim($_POST['userInfo']);

    if (empty($userInfo)) {
        $result = "<div class='alert alert-danger alert-dismissible'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Email Field Must Not Be Empty</div>";
    } else {
        // Use prepared statements to prevent SQL injection
        $query = $connect_db->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
        $query->bind_param('ss', $userInfo, $userInfo);
        $query->execute();
        $resultQuery = $query->get_result();

        if ($resultQuery->num_rows === 0) {
            $result = "<div class='alert alert-danger alert-dismissible'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Email Address or Username Does Not Exist</div>";
        } else {
            $userRow = $resultQuery->fetch_assoc();
            $userEmail = $userRow['email'];
            
            $user_id = $userRow['user_id'];
            $code = $user_id . '_' .sha1(uniqid(true) . date("Y-m-d H:i:s"));
            $is_active = 'active';
            $query->close();

            // Use prepared statements to insert reset code
            $query2 = $connect_db->prepare("INSERT INTO resetpasswords (code, email, used ,date) VALUES (?, ?, ?,NOW())");
            $query2->bind_param('sss', $code, $userEmail,$is_active);
            $query2->execute();

            if (!$query2) {
                exit("Error Occurred: " . mysqli_error($connect_db));
            }
            
            include('forgot_mailer.php');
            $result = "<div class='alert alert-success alert-dismissible'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Reset link sent to your email. Please check your inbox.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Reset Password</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-6 mx-auto shadow p-4">
                <h4 class="text-success text-center">Reset Password</h4>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                    <div class="mb-3">
                        <input type="text" name="userInfo" placeholder="Username or Email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="submit" name="submit" class="btn btn-success w-100" value="Reset Password">
                    </div>
                    <p class="text-center">Don't have an account? <a href="signup.php">Sign up here</a></p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

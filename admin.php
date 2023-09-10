<?php
session_start();
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $role = $_SESSION['role'];
} else{
    header("Location: signin.php");
}

if ($role != 'admin') {
    // Redirect to a restricted access page or show an error message
    header("Location: access_denied.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <h1 class="text text-success text-center mx-auto">Flag:Congratulations!! you have gained Access !!</h1>
</body>
</html>
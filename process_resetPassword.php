<?php
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);
require "dbcredentials.php";
$msg = ""; 
if (!isset($_POST['token'])){
    header('Location: notFound.php');
}

$token =$_POST['token'];
$getEmail = $connect_db->query("SELECT * from resetpasswords  WHERE code ='$token'");
$row = $getEmail->fetch_row();
$token = $row[1];
$emailGot = $row[2];

if (mysqli_num_rows($getEmail) == 0) {
    exit(include("notFound.php"));
}

if ($row[3] == 'used' || $row[3] == 'expired')
{
    exit(include("sessionTimeout.php"));
}

date_default_timezone_set("Africa/Lagos");
$sql1= "SELECT TIMESTAMPDIFF (SECOND, date, NOW()) AS tdif FROM resetpasswords WHERE code ='$token'";
$result= $connect_db->prepare($sql1);
$result->execute();
$result->store_result();
$result->bind_result($tdif);
$result->fetch();

if ($tdif >= 900) {
    $connect_db->query("UPDATE resetpasswords SET `used` = 'expired' WHERE code = '$token'");
    exit(include("sessionTimeout.php"));
}

if (isset($_POST['submit'])) {
    $password = mysqli_real_escape_string($connect_db, $_POST['password']);
    $cpassword = mysqli_real_escape_string($connect_db, $_POST['cpassword']);
//     //regex
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if ($password =="" || $cpassword =="") {
        $msg ="<div class='alert alert-danger alert-dismissable><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> Password Field cannot be Empty</div>";
    }
    else {
        if ($password != $cpassword) {
            $msg ="<div class='alert alert-danger alert-dismissable><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> Password did not match!! please try again !!</div>";
        }
        elseif (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) > 8){

             $msg ="<div class='alert alert-danger alert-dismissable><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> Password  must be 8 characters in length with at least one uppercase and  lowercase letter, one numeric and special character <strong> e.g ctf123@1</strong></div>";
        }
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = mysqli_query($connect_db, "UPDATE users SET `password` = '$password' WHERE email='$emailGot'");

        if ($query) {
            $connect_db->query("UPDATE resetpasswords SET `used` = 'used' WHERE code = '$token'");


            $msg ="<div class='alert alert-danger alert-dismissable><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> Password Updated! successfully <a class='loginHome' href='login.php'>Click to login</a></div>";

            header("Location: signin.php");
        }
        else{

            $msg ="<div class='alert alert-danger alert-dismissable><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> Something went wrong! contact the Admin.!</div>";
        
        }
    }
}



?>
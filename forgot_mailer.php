<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

// Load environment variables from .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

function smtpmailer($to,$subject, $message,$username){
    
    $mail = new PHPMailer(true);
    $mail->isMail();
    $mail->setFrom($_ENV['email_address'], 'ctf');
    $mail->addAddress($to, $username);
    $mail->Subject=$subject;
    $mail->Body= $message;
    try {
        $mail->send();
        return "<div class='alert alert-success alert-dismissable><a href='#' class='close' data-dismiss='success' aria-label='close'>&times;</a> Please check Your Email Inbox or your spam folder!</div>";
    } catch (Exception $e) {
        return "<div class='alert alert-danger alert-dismissable><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> Please an error occurred({$mail->ErrorInfo}), kindly contact the Admin.</div>";
    }
}

$to = $userEmail;
$username = $userRow['username'];
$subj = 'Reset Password';
$msg = '
        Hi,<br><br>
    In order to reset your password, please click on the link below:<br><br>
    <a href="'.($_SERVER['HTTPS'] ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . "/reset_password.php?token=".$code.'">Reset</a><br><br> or copy and paste the link in a new tab <br><br>

       '.($_SERVER['HTTPS'] ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . "/reset_password.php?token=".$code
        .'<br><br> 

        Kind Regards, <br><br>
        ctf_project
';


$result=smtpmailer($to,$subj,$msg, $username);
echo $result;
 ?>

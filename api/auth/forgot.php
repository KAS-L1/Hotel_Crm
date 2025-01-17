<?php require("../../app/init.php") ?>

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require("../../vendor/autoload.php");
require("jwt.php");


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function storeOldInput($key, $value)
{
    $_SESSION['old'][$key] = $value;
}

if (!isset($_POST['email'])) die(toast("error", "Please enter a valid email address"));

$email = $DB->ESCAPE(VALID_MAIL($_POST['email']));

storeOldInput('email', $_POST['email']);

$user = $DB->SELECT_ONE_WHERE("users", "*", array("email" => $email));
if (empty($user)) die(toast("error", "Email not found in our records. Please check your email address."));

// Generate a JWT token
$jwt = new JWT("this-is-my-password-token-recovery"); 
$payload = [
    "user_id" => $user['user_id'],
    "email" => $user['email'],
    "time" => time(),
    "exp" => strtotime('+1 hour')
];
$token = $jwt->createToken($payload);

$resetLink = $_SERVER['HTTP_HOST'] . "/recover?token=" . urlencode($token);

try {
    $mail = new PHPMailer(true);
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'kasl.54370906@gmail.com';
    $mail->Password = 'lgrg mpma cwzo uhdv';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Set sender and recipient
    $mail->setFrom('kasl.54370906@gmail.com', 'Logistic Paradise');
    $mail->addAddress($email);

    // Email content
    $mail->isHTML(true);
    $mail->Subject = "Password Reset Request";
    $mail->Body = "
            <p>We received a request to reset your password. Click the link below to reset your password:</p>
            <p><a href='$resetLink'>$resetLink</a></p>
            <p>If you did not request a password reset, please ignore this email.</p>
        ";
    $mail->AltBody = "We received a request to reset your password. Click the link below to reset your password: $resetLink. If you did not request a password reset, please ignore this email.";

    unset($_SESSION['old']);

    if ($mail->send()) {
        toast("success", "A password reset link has been sent to $email. Please check your inbox.");
        refresh(1200);
    } else {
        toast("error", "Failed to send the password reset link. Please try again later.");
    }
} catch (Exception $e) {
    toast("error", "Message could not be sent. Error: {$mail->ErrorInfo}");
}


<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

// Initialize the $mail variable globally
$mail = initializeMailer();

function initializeMailer()
{

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'celestial32202';
    $mail->Password   = 'zbdr bfwm bitq vrmj';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->setFrom('celestial32202@gmail.com');
    return $mail;
}
function sendEmail($email, $subject, $body)
{
    global $mail;
    try {
        $mail->clearAddresses(); // Clear previous recipients
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function getBaseURL()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST']; // Gets localhost or domain
    $path = dirname($_SERVER['SCRIPT_NAME']); // Gets the project path

    return rtrim($protocol . $host . $path, '/');
}
function sendAccountVerificationEmail($email, $first_name, $password, $token)
{
    $baseURL = getBaseURL(); // Auto-detect localhost or live domain
    $resetLink = "$baseURL/acc-activation.php?token=" . urlencode($token);

    $subject = "Account Activation & Password Reset";
    $body = "Hello $first_name,<br><br>
            <br>
             Your account has been created. Your temporary password is: <b>$password</b><br>
             To set your new password, click the link below:<br>
             <a href='$resetLink'>$resetLink</a><br><br>
             This link will expire in 1 hour.";

    return sendEmail($email, $subject, $body);
}

function sendPasswordResetEmail($email, $token)
{
    $baseURL = getBaseURL(); // Gets base URL automatically
    $resetLink = "$baseURL/reset-password.php?token=" . urlencode($token);

    $subject = "Password Reset Request";
    $body = "Hello,<br><br>
             We received a request to reset your password.<br>
             Click the link below to reset it:<br><br>
             <a href='$resetLink'>$resetLink</a><br><br>
             This link will expire in 1 hour.<br><br>
             If you didn't request this, please ignore this email.";

    return sendEmail($email, $subject, $body);
}
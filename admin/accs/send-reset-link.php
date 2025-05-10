<?php
session_start();
require_once '../../config.php'; // MySQLi connection
require_once 'email-sender.php';
require_once '../configs/jwt_handler.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = trim($_POST['email']);

    // Sanitize email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Check if the email exists
    $stmt = $conn->prepare("SELECT * FROM admin_creds WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $token = JWTHandler::encodeResetToken($email, 3600); // 1 hour expiry

        if (sendPasswordResetEmail($email, $token)) {
            $_SESSION['forg_pass_info'] = "<div class='alert alert-info'>A password reset link has been sent to your email.</div>";
        } else {
            $_SESSION['forg_pass_error'] = "<div class='alert alert-danger'>Failed to send the email. Please try again.</div>";
        }
    } else {
        // Optional: still show success to avoid email enumeration

        $_SESSION['forg_pass_info'] = "<div class='alert alert-info'>A password reset link has been sent to your email.</div>";
    }

    header("Location: ../forgot-password.php");
    exit();
}
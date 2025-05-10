<?php
session_start();
require_once('../../config.php');

// Check if the user is logged in
if (isset($_SESSION['ADMIN_SESSION'])) {
    $email = $_SESSION['ADMIN_SESSION'];

    // Clear session from the database (if applicable)
    $query = "UPDATE admin_creds SET session_id = NULL WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->close();
}

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Regenerate session ID for security
session_start();
session_regenerate_id(true);

// Redirect to login page
header('Location: ../index.php');
exit();

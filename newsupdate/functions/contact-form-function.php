<?php
require_once '../config.php';

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$response = array('success' => false, 'message' => 'An error occurred.');

// Check if form fields are set
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['message'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));
    $sql = "INSERT INTO contact_msg (contact_name, contact_email, contact_subject, contact_message) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    try {
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);
        if (mysqli_stmt_execute($stmt)) {
            $response['success'] = true;
            $response['message'] = 'Thank you for your message. We will get back to you shortly.';
        } else {
            $response['message'] = 'An error occurred while processing your request. Please try again later.';
        }
        mysqli_stmt_close($stmt);
       
        
    } catch(PDOException $e) {
        $response = array('success' => false, 'message' => 'An error occurred while processing your request. Please try again later.');
    }
} else {
    $response['message'] = 'Please fill in all required fields.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>
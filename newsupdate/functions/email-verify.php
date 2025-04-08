<?php
require_once('../config.php');
session_start();

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $query = "SELECT * FROM users_creds WHERE verif_token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['acc_status'] == 'notverified') {
            $updateQuery = "UPDATE users_creds SET acc_status = 'verified', verif_token = NULL, verif_code = '0' WHERE verif_token = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("s", $token);
            $stmt->execute();

            $_SESSION['info'] = "<div class='alert alert-success'>Your email has been verified successfully!</div>";
        } else {
            $_SESSION['info'] = "<div class='alert alert-info'>Your email is already verified.</div>";
        }
    } else {
        $_SESSION['errors'] = "<div class='alert alert-danger'>Invalid verification link.</div>";
    }
} else {
    $_SESSION['errors'] = "<div class='alert alert-danger'>No verification token provided.</div>";
}

header('Location: ../forms/loginform.php');
die();
?>

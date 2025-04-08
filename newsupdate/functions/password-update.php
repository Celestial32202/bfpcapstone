<?php
include('../config.php');
session_start();
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['pass_update_btn'])) {   
    $id_prfl = $_POST['pass_id'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Retrieve the current user's password hash from the database
    $query = "SELECT hashed_password FROM users_creds WHERE id_counter = '$id_prfl'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $stored_password = $row['hashed_password'];

    // Verify if the old password matches the stored password
    if (password_verify($old_password, $stored_password)) {
        // Check if the new password matches the confirm password
        if ($new_password === $confirm_password) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE users_creds SET hashed_password = '$hashed_password' WHERE id_counter = '$id_prfl'";
            $update_result = mysqli_query($conn, $update_query);
            if ($update_result) {
                // Password updated successfully
                $_SESSION['info'] = "<div class='alert alert-success' role='alert' id='notification'>Password updated successfully!</div>";
            } else {
                // Error updating password in the database
                $_SESSION['info'] = "<div class='alert alert-warning' role='alert' id='notification'>Database error!</div>";
            }
        } else {
            // New password and confirm password do not match
            $_SESSION['info'] = "<div class='alert alert-warning' role='alert' id='notification'>New password and confirm password do not match!</div>";
        }
    } else {
        // Old password is incorrect
        $_SESSION['info'] = "<div class='alert alert-warning' role='alert' id='notification'>Incorrect old password!</div>";
    }

    // Redirect back to user profile page
    header('Location: ../user-profile.php');
    exit();
} else {
    // Redirect to user profile page if accessed directly without form submission
    header('Location: ../user-profile.php');
    exit();
}
?>
?>
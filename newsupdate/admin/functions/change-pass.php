<?php
require_once('config.php');
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if (isset($_POST['psswrd_update_btn'])) {
    $oldPassword = $_POST['old_pword'];
    $newPassword = $_POST['new_pword'];
    $repeatPassword = $_POST['rpt_new_pword'];

    if (empty($oldPassword)) {
        $_SESSION['info'] = "<div class='alert alert-danger'>Old password is required!</div>";
    } else {
        $email = $_SESSION['SESSION_ADMIN'];
        $selectQuery = "SELECT password FROM admin_acc WHERE email = '$email'";
        $result = mysqli_query($conn, $selectQuery);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $db_oldPassword = $row['password'];

            if ($oldPassword === $db_oldPassword) {
                if (empty($newPassword)) {
                    $_SESSION['info'] = "<div class='alert alert-danger'>New password is required!</div>";
                } elseif ($newPassword !== $repeatPassword) {
                    $_SESSION['info'] = "<div class='alert alert-danger'>New passwords do not match!</div>";
                } else {

                    $updateQuery = "UPDATE admin_users SET password = '$newPassword' WHERE email = '$email'";
                    $updateResult = mysqli_query($conn, $updateQuery);
                    if ($updateResult) {
                        $_SESSION['info'] = "<div class='alert alert-success'>Password updated successfully!</div>";
                        header("Location: adminprofile.php");
                        exit();
                    } else {
                        $_SESSION['info'] = "<div class='alert alert-danger'>Error updating password: " . mysqli_error($conn) . "</div>";
                    }
                }
            } else {
                $_SESSION['info'] = "<div class='alert alert-danger'>Incorrect old password!</div>";
            }
        } else {
            $_SESSION['info'] = "<div class='alert alert-danger'>Error retrieving old password: " . mysqli_error($conn) . "</div>";
        }
    }
} elseif (isset($_POST['cncl_btn'])) {
    header("Location: adminprofile.php");
    exit();
}
?>

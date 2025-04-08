<?php
include('../config.php');
session_start();
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    if (isset($_POST['acc_update_btn'])) {   
        $id = $_POST['acc_id'];
        $first_name = !empty($_POST['first_name']) ? mysqli_real_escape_string($conn, $_POST['first_name']) : null;
        $middle_name = !empty($_POST['middle_name']) ? mysqli_real_escape_string($conn, $_POST['middle_name']) : null;
        $last_name = !empty($_POST['last_name']) ? mysqli_real_escape_string($conn, $_POST['last_name']) : null;
        $phone_number = !empty($_POST['phone_number']) ? mysqli_real_escape_string($conn, $_POST['phone_number']) : null;
    
        $update_fields = [];
        if ($first_name !== null) {
            $update_fields[] = "first_name = '$first_name'";
        }
        if ($middle_name !== null) {
            $update_fields[] = "middle_name = '$middle_name'";
        }
        if ($last_name !== null) {
            $update_fields[] = "last_name = '$last_name'";
        }
        if ($phone_number !== null) {
            $update_fields[] = "phone_number = '$phone_number'";
        }
    
        if (!empty($update_fields)) {
            $update_query = "UPDATE users_creds SET " . implode(", ", $update_fields) . " WHERE id_counter = '$id'";
            $update_query_run = mysqli_query($conn, $update_query);
    
            if ($update_query_run) {
                $_SESSION['info'] = "<div class='alert alert-success' role='alert' id='notification'>Profile Information Updated!</div>";
            } else {
                $_SESSION['info'] = "<div class='alert alert-warning' role='alert' id='notification'>Database error!</div>";
            }
        } else {
            $_SESSION['info'] = "<div class='alert alert-warning' role='alert' id='notification'>No fields to update!</div>";
        }
        
        header('Location: ../user-profile.php');
        exit();
    }

?>
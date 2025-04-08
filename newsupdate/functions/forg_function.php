<?php
require_once('email-sender.php');
require ('../config.php');
session_start();

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if(isset($_POST['forgot_pass']) || isset($_POST['resend_otp'])){
    if((isset($_POST['email']) || isset($_SESSION['info_email'])) && empty($_POST['otp_input_box'])){
            if (isset($_POST['email'])) {
                $email = $_POST['email'];
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $_SESSION['forg_pass_error'] = "<div class='alert alert-danger'>Input a valid email address!</div>";
                    header('Location: ../forgot-pass/forgot-password.php');
                    exit();
                } else {
                    $email = mysqli_real_escape_string($conn, $_POST['email']);
                    send_forg_pass_email($conn, $email);
                    $_SESSION['forg_pass_info']= "<div class='alert alert-info'>We have sent the reset code to your email</div>";
                    $_SESSION['info_email'] = $email;
                    header('Location: ../forgot-pass/otp-verify.php');
                    exit();
                }
            }
            elseif (isset($_SESSION['info_email'])) {
                $email = $_SESSION['info_email'];
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $_SESSION['forg_pass_error'] = "<div class='alert alert-danger'>Input a valid email address!</div>";
                    header('../forgot-pass/otp-verify.php');
                    exit();
                } else {
                    $email = mysqli_real_escape_string($conn, $_SESSION['info_email']);
                    send_otp_code_email($conn,$email);
                    $_SESSION['forg_pass_info'] = "<div class='alert alert-info'>New code has been sent on your email</div>";
                    $_SESSION['info_email'] = $email;
                    header('Location: ../forgot-pass/otp-verify.php');
                    exit();
                }
            }
            
    }
    elseif (!empty($_POST['otp_input_box']) && isset($_POST['forgot_pass'])) {
            $email = mysqli_real_escape_string($conn, $_SESSION['info_email']);
            $check_email = "SELECT * FROM users_creds WHERE email='$email'";
            $fetch_data = mysqli_fetch_assoc(mysqli_query($conn, $check_email));
            $fetched_code = $fetch_data['verif_code'];
            $otp_code = mysqli_real_escape_string($conn, $_POST['otp_input_box']);
        
            if($otp_code == $fetched_code){        
                $otp_code = 0;
                $update_otp = "UPDATE users_creds SET verif_code = $otp_code WHERE verif_code = $fetched_code";
                $update_res = mysqli_query($conn, $update_otp);
                if($update_res){
                    $_SESSION['info_email'] = $fetch_data['email'];
                    header('location: ../forgot-pass/change-password.php');
                    exit();
                }else{
                    $_SESSION['forg_pass_error'] = "<div class='alert alert-danger'>Failed while updating code!</div>";
                    header('Location: ../forgot-pass/otp-verify.php');
                    exit();
                }
            }else{
                
                $_SESSION['forg_pass_error'] = "<div class='alert alert-danger'>You've entered incorrect code! $fetched_code</div>";
                header('Location: ../forgot-pass/otp-verify.php');
                exit();
            }
    }
    elseif (!empty($_POST['otp_input_box']) && isset($_POST['resend_otp']) && isset($_SESSION['info_email'])){
            $email = $_SESSION['info_email'];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['forg_pass_error'] = "<div class='alert alert-danger'>Input a valid email address!</div>";
                header('../forgot-pass/otp-verify.php');
                exit();
            } else {
                $email = mysqli_real_escape_string($conn, $_SESSION['info_email']);
                send_otp_code_email($conn,$email);
                $_SESSION['forg_pass_info'] = "<div class='alert alert-info'>New code has been sent on your email</div>";
                $_SESSION['info_email'] = $email;
                header('Location: ../forgot-pass/otp-verify.php');
                exit();
            }
    }
}
if (isset($_POST['change_email'])){
    unset($_SESSION['forg_pass_info']);
    unset($_SESSION['forg_pass_error']);
    unset($_SESSION['info_email']);
    header('Location: ../forgot-pass/forgot-password.php');
    exit();
}
?>
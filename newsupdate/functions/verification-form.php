<?php require_once "email-sender.php"; ?>
<?php
include "../config.php";
session_start();

// if (isset($_SESSION['SESSION_EMAIL'])) {
//     header('Location: ../index.php');
//     die();
// }
if(!isset($_SESSION['verify_email_reg'])){ //will check if this form is accessed through regform 
    header('Location: registerform.php');
    die();
}else{
    $entered_email = mysqli_real_escape_string($conn, $_SESSION['verify_email_reg']);
}
if(isset($_SESSION['verify_email_login'])){
    $_SESSION['email'] = mysqli_real_escape_string($conn, $_SESSION['SESSION_EMAIL']);
    $entered_email = mysqli_real_escape_string($conn, $_SESSION['verify_email_login']);
    send_otp_code_email($conn, $_SESSION['email']);
    unset($_SESSION['verify_email_login']);
}

if(isset($_POST['submit'])){
    $check_email = "SELECT * FROM users_creds WHERE email = '$entered_email'";
    $fetch_data = mysqli_fetch_assoc(mysqli_query($conn, $check_email));
    $fetched_code = $fetch_data['verif_code'];
    $otp_code = mysqli_real_escape_string($conn, $_POST['otp_code']);

    if($otp_code == $fetched_code){        
        $otp_code = 0;
        $status = 'verified';        
        $update_otp = "UPDATE users_creds SET verif_code = ? , status = ? WHERE verif_code = ?";
        $stmt = $conn->prepare($update_otp);
        $stmt->bind_param("iss", $otp_code, $status, $fetched_code);
        $stmt->execute();
        if($stmt){
            $_SESSION['info'] =  "<div class='alert alert-success'>Your account {$fetch_data['email']} is now verified, you may log-in now</div>";
            unset($_SESSION['verify_email_reg']);
            header('Location: ../loginform.php');
            die();
        }else{
            
            $_SESSION['errors']= "<div class='alert alert-danger'>Failed while updating code!</div>";
            header('Location: verification-form.php');
            die();
        }
    }else{
        $_SESSION['errors'] = "<div class='alert alert-danger'Entered: $otp_code, Fetched: $fetched_code</div>";

        header('Location: verification-form.php');
        die();
    }
}
if(isset($_POST['back_to_regform'])){
    $entered_email = mysqli_real_escape_string($conn, $_SESSION['email']);
    $delete_nonverified_acc = "DELETE FROM users_creds WHERE email = ?";
    $stmt = $conn->prepare($delete_nonverified_acc);
    $stmt->bind_param("s", $entered_email);
    $stmt->execute();
    header('Location: registerform.php');
}
if(isset($_POST['resend_otp'])){
    $email = mysqli_real_escape_string($conn, $_SESSION['email']);
    send_otp_code_email($conn, $email);
    $_SESSION['info'] = "<div class='alert alert-info'>Verification OTP has been resent</div>";
}
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <title>FAS Development Corporation</title>
    <link rel="icon" href="../img/form-img/logo.png.webp" type="image/x-icon">
    <link href="//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/loginform.css" type="text/css" media="all" />
    <script src="https://kit.fontawesome.com/af562a2a63.js" crossorigin="anonymous"></script>
</head>
<body>
    <section class="w3l-mockup-form">
        <div class="container">
            <div class="workinghny-form-grid">
                <div class="main-mockup">
                    <div class="alert-close">
                        <span class="fa fa-close"></span>
                    </div>
                    <div class="w3l_form align-self">
                        <div class="lft_grid_info">
                        <img src="../img/form-img/logo.png.webp" alt="">
                        </div>
                    </div>
                    <div class="content-wthree">
                        <h2>Accoutn Verification</h2>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
                        <?php 
                            if(isset($_SESSION['errors'])){
                                    echo $_SESSION['errors'];
                                    unset($_SESSION['errors']);
                            }
                            if (isset($_SESSION['info'])) {
                                    echo $_SESSION['info'];
                                    unset($_SESSION['info']);
                            }
                        ?>
                        <form action="" method="post">
                        <input type="text" class="otp_box" name="otp_code" placeholder="Enter your OTP Code" 
                        oninput="limitInput(this, 6)" onkeypress="return restrictInput(event)">
                        <div class="links_forg_pass">
                            <p><input type="submit" class="rsnd_otpcode" name="resend_otp" value="Re-Send OTP" ></p>
                        </div>
                            <button name="submit" class="btn" type="submit">Submit</button>
                        </form>
                        <form action="" method="post">
                        <div class="social-icons">
                            <p>Back to! <input type="submit" class="links_rst_otpcode" name="back_to_regform" value="Register">.</p>
                            
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="../js/jquery.min.js"></script>
    <script>
        $(document).ready(function (c) {
            $('.alert-close').on('click', function (c) {
                $('.main-mockup').fadeOut('slow', function (c) {
                    $('.main-mockup').remove();
                });
            });
        });
        function restrictInput(event) {
            if (event.which < 48 || event.which > 57) {
                event.preventDefault();
            }
            }
            function limitInput(element, maxLength) {
            if (element.value.length >= maxLength) {
                element.value = element.value.slice(0, maxLength);
            }
            }
    </script>

</body>

</html>
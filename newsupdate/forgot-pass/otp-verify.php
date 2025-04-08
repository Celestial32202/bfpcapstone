<?php
session_start();
if (isset($_POST['action']) && $_POST['action'] == 'clearSession') {
    unset($_SESSION['forg_pass_errors']);
    unset($_SESSION['forg_pass_info']);
    unset($_SESSION['info_email']);
}
if(!isset($_SESSION['info_email'])){
    header('Location: forgot_password.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
<title>SKM</title>
    <link rel="icon" href="../images/proper-logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="../css/loginform.css" type="text/css" media="all" />
    
</head>
<body>
    <section class="w3l-mockup-form">
        <div class="container">
            <div class="workinghny-form-grid">
                <div class="main-mockup">
                    <div class="alert-close">
                        <span class="fa fa-close"></span>
                    </div>
                    
                    <div class="content-wthree">
                        <h2>Forgot Password</h2>
                        <p>Check your email for the verification code.</p>
                        <?php 
                            if(isset($_SESSION['forg_pass_error'])){
                                echo $_SESSION['forg_pass_error'];
                                unset($_SESSION['forg_pass_error']);
                            }
                            if (isset($_SESSION['forg_pass_info'])) {
                                echo $_SESSION['forg_pass_info'];
                                unset($_SESSION['forg_pass_info']);
                            }
                        ?>
                        <form action="../functions/forg_function.php" method="post" >
                            <input type="text" class="otp_box"name="otp_input_box" placeholder="Enter your OTP Code" value=""
                            oninput="limitInput(this, 6)" onkeypress="return restrictInput(event)" >
                            <div class="links_forg_pass">
                            <p><input type="submit" class="links_rst_otpcode" name="resend_otp" value="Re-Send OTP" ></p>
                            <p><input type="submit" class="links_change_email" name="change_email" value="Change_Email"></p>
                            </div>
                            <button name="forgot_pass" class="btn" type="submit" >Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="../css/jquery.min.js"></script>
    <script>
        $(document).ready(function (c) {
            $('.alert-close').on('click', function (c) {
                $('.main-mockup').fadeOut('slow', function (c) {
                    $('.main-mockup').remove();
                    $.ajax({
                        url: '', 
                        method: 'POST',
                        data: { action: 'clearSession' },
                        success: function() {
                            window.location.href = "../forms/loginform.php";
                        }
                    });
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
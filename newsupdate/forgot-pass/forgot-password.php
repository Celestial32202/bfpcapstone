<?php
session_start();
unset($_SESSION['info_email']);
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
                        <p> Input your email to reset password!</p>
                        <?php
                                if(isset($_SESSION['forg_pass_info'])){
                                        echo $_SESSION['forg_pass_info'];
                                        unset($_SESSION['forg_pass_info']);
                                }
                                if (isset($_SESSION['forg_pass_error'])) {
                                        echo $_SESSION['forg_pass_error'];
                                        unset($_SESSION['forg_pass_error']);
                                }
                            ?>
                        <form action="../functions/forg_function.php" method="post" >
                            <input type="text" class="email_changepass" name="email" placeholder="Enter your Email Address" required>
                            <button name="forgot_pass" class="btn" type="submit" >Submit</button>
                        </form>
                        <div class="social-icons">
                            <p>Back to! <a href="../forms/loginform.php">Login</a>.</p>
                        </div>
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
                    window.location.href = "../forms/loginform.php";
                });
            });
            
        });
    </script>

</body>
</html>
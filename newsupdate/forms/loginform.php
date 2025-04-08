<?php
require_once('../config.php');
session_start();

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION['SESSION_EMAIL'])) {
    header('Location: ../index.php');
    exit();
}
if (isset($_POST['login_acc_btn'])) {
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $recaptchaSuccess = verifyRecaptcha($recaptchaResponse);
    if ($recaptchaSuccess) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $check_email = "SELECT * FROM users_creds WHERE email = '$email'";
        $res = mysqli_query($conn, $check_email);

        $check_admin = "SELECT * FROM admin_acc WHERE email = '$email'";
        $res_admin = mysqli_query($conn, $check_email);

        if (mysqli_num_rows($res) > 0) {
            $fetch = mysqli_fetch_assoc($res);
            $fetch_pass = $fetch['hashed_password'];
            if (password_verify($password, $fetch_pass)) {
                $_SESSION['email'] = $email;
                $status = $fetch['acc_status'];
                if ($status == 'verified') {
                    $_SESSION['SESSION_EMAIL'] = $email;
                    header('Location: ../index.php');
                    die();
                } else {
                    $_SESSION['info'] = "<div class='alert alert-warning'>It looks like you haven't still verified your email - $email
                    We have sent a new verification code</div>";
                    $_SESSION['verify_email_login'] = $email;
                    header('Location: login-verification-form.php');
                    die();
                }
            } else {
                $_SESSION['errors'] = "<div class='alert alert-danger'>Incorrect email or password!</div>";
                header('Location: loginform.php');
                die();
            }
        } else {
            $check_admin = "SELECT * FROM admin_acc WHERE email = '$email'";
            $res_admin = mysqli_query($conn, $check_admin);
            if (mysqli_num_rows($res_admin) > 0) {
                $fetch_admin = mysqli_fetch_assoc($res_admin);
                $fetch_admin_pass = $fetch_admin['password'];
                if (password_verify($password, $fetch_admin_pass)) {
                    $_SESSION['SESSION_ADMIN'] = $email;
                    header('Location: ../admin/dashboard.php');
                    die();
                } else {
                    $_SESSION['errors'] = "<div class='alert alert-danger'>Incorrect email or password!</div>";
                    header('Location: loginform.php');
                    die();
                }
            } else {
                $_SESSION['info'] = "<div class='alert alert-info'>It's look like you're not a member yet! Click on the bottom link to signup.</div>";
                header('Location: loginform.php');
                die();
            }
        }
    } else {
        $_SESSION['errors'] = "<div class='alert alert-danger'>reCAPTCHA verification failed. Please try again.</div>";
        header('Location: loginform.php');
        die();
    }
}
function verifyRecaptcha($response)
{
    $secretKey = '6Lc489wpAAAAAFmwdYmJI6sPs0mfckACzxRVF-xC';
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $secretKey,
        'response' => $response
    );
    $options = array(
        'http' => array(
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) {
        return false;
    } else {
        $json = json_decode($result, true);
        return $json['success'];
    }
}

?>
<!DOCTYPE html>

<head>
    <title>SKM</title>
    <link rel="icon" href="../images/proper-logo.jpg" type="image/x-icon">
    <link href="//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="../css/loginform.css" type="text/css" media="all" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="../css/jquery.min.js"></script>
</head>

<body>
    <section class="w3l-mockup-form">
        <div class="container">
            <div class="main-mockup">
                <div class="alert-close">
                    <span class="fa fa-close"></span>
                </div>
                <div class="content-wthree">
                    <h2>LOGIN</h2>
                    <?php
                    if (isset($_SESSION['errors'])) {
                        echo $_SESSION['errors'];
                        unset($_SESSION['errors']);
                    }
                    if (isset($_SESSION['info'])) {
                        echo $_SESSION['info'];
                        unset($_SESSION['info']);
                    }
                    ?>
                    <form id="login_form" name="login_form" action="" method="post">
                        <input type="email" class="email" name="email" placeholder="Enter Your Email" required>
                        <div class="login_input_pass">
                            <input type="password" id="password" class="password" name="password"
                                placeholder="Enter Your Password" style="margin-bottom: 2px;" required>
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <div class="forg_pass">
                            <p><a href="../forgot-pass/forgot-password.php">Forgot Password?</a></p>
                        </div>
                        <div class="google-captcha">
                            <div class="g-recaptcha" data-sitekey="6Lc489wpAAAAALErVt_h0RVHokOAM_HGMoLdk2j_"></div>
                        </div>
                        <button name="login_acc_btn" class="btn" type="submit">Login</button>
                    </form>
                    <div class="create_acc">
                        <p>Create Account! <a href="registerform.php">Register</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="../js/script.js"></script>
    <script>
    $(document).ready(function(c) {
        $('.alert-close').on('click', function(c) {
            $('.main-mockup').fadeOut('slow', function(c) {
                $('.main-mockup').remove();
                window.location.href = "../index.php";
            });
        });
    });
    const passwordInput = document.getElementById("password");
    const eyeIcon = document.querySelector('i');
    eyeIcon.addEventListener("click", () => {
        passwordInput.type = passwordInput.type === "password" ? "text" : "password";
        eyeIcon.className = `fa-solid fa-eye${passwordInput.type === "password" ? "" : "-slash"}`;
    });
    </script>
</body>

</html>
<?php
require_once 'configs/auth.php';
require_once 'configs/jwt_handler.php';
redirectIfLoggedIn();
// If the user is already logged in, redirect them to the dashboard

if (isset($_POST['login_acc_btn'])) {
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $recaptchaSuccess = verifyRecaptcha($recaptchaResponse);
    if ($recaptchaSuccess) {
        $user = mysqli_real_escape_string($conn, $_POST['user']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        if ($user === 'coh_admin') {
            $stmt = $conn->prepare("SELECT * FROM admin_creds WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $user);
        } else {
            $query = "SELECT * FROM admin_creds WHERE email = ? LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $user);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $fetch = $result->fetch_assoc();
            $user = $fetch['email'];
            // Check if account is locked
            if ($fetch['is_locked']) {
                $_SESSION['errors'] = "<div class='alert alert-danger'>Account locked due to too many failed login attempts. Contact Admin</div>";
                header("Location: index.php");
                exit();
            }
            if ($fetch['is_deleted']) {
                $_SESSION['errors'] = "<div class='alert alert-danger'>Account deleted. Contact Admin for inquiries.</div>";
                header("Location: index.php");
                exit();
            }

            $fetch_pass = $fetch['password'];
            if (password_verify($password, $fetch['password'])) {
                if (!$fetch['verified']) {
                    if ($fetch['expiredToken']) {
                        $_SESSION['errors'] = "<div class='alert alert-danger'>Account verification link expired. Please contact the admin.</div>";
                        header("Location: index.php");
                        exit();
                    } else {
                        $token = $fetch['jwt_token'];

                        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
                        $host = $_SERVER['HTTP_HOST']; // localhost or domain
                        $path = dirname($_SERVER['SCRIPT_NAME']); // project path (e.g. /myapp)

                        $baseURL = rtrim($protocol . $host . $path, '/');

                        header("Location: " . $baseURL . "/accs/acc-activation.php?token=" . urlencode($token));
                        exit();
                    }
                }
                // Generate a new session ID
                function generateUniqueSessionId($conn, $user)
                {
                    $count = 0;
                    do {
                        session_regenerate_id(true);
                        $new_session_id = session_id();

                        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM admin_creds WHERE session_id = ?");
                        $check_stmt->bind_param("s", $new_session_id);
                        $check_stmt->execute();
                        $check_stmt->bind_result($count);
                        $check_stmt->fetch();
                        $check_stmt->close();
                    } while ($count > 0);

                    $update_stmt = $conn->prepare("UPDATE admin_creds SET session_id = ? WHERE email = ?");
                    $update_stmt->bind_param("ss", $new_session_id, $user);
                    $update_stmt->execute();
                    $update_stmt->close();

                    return $new_session_id;
                }

                // Reset failed attempts on successful login
                $reset_stmt = $conn->prepare("UPDATE admin_creds SET failed_attempts = 0, last_failed_login = NULL WHERE id = ?");
                $reset_stmt->bind_param("i", $fetch['id']);
                $reset_stmt->execute();
                // Store user session
                $new_session_id = generateUniqueSessionId($conn, $user); // <-- use email value
                $_SESSION['branch'] = $fetch['branch'];
                $_SESSION['admin_user'] = $fetch['last_name'];
                $_SESSION['ADMIN_SESSION'] = $user;
                $_SESSION['SESSION_ID'] = $new_session_id; // Store session ID for validation
                $_SESSION['position'] = $fetch['admin_position'];
                $_SESSION['permissions'] = json_decode($fetch['admin_permissions'], true);
                // Redirect based on position
                switch ($fetch['admin_position']) {
                    case 'Command Officer Head':
                        header("Location: dashboard.php");
                        break;
                    case 'Command Officer Staff':
                        header("Location: dashboard.php");
                        break;
                    case 'Fire Officer Supervisor':
                        header("Location: dashboard.php");
                        break;
                    case 'Fire Officer':
                        header("Location: dashboard.php");
                        break;
                    default:
                        header("Location: index.php");
                }
                exit();
            } else {
                // ❌ WRONG PASSWORD
                $failed_attempts = $fetch['failed_attempts'] + 1;
                $is_locked = $failed_attempts >= 3 ? 1 : 0;
                $update_stmt = $conn->prepare("UPDATE admin_creds SET failed_attempts = ?, last_failed_login = NOW(), is_locked = ? WHERE id = ?");
                $update_stmt->bind_param("iii", $failed_attempts, $is_locked, $fetch['id']);
                $update_stmt->execute();
                $_SESSION['errors'] = "<div class='alert alert-danger'>Incorrect username or password!" . ($is_locked ? " Account has been locked." : "") . "</div>";
                header("Location: index.php");
                exit();
            }
        } else {
            $_SESSION['errors'] = "<div class='alert alert-danger'>Incorrect username or password!</div>";
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['errors'] = "<div class='alert alert-danger'>reCAPTCHA verification failed. Please try again.</div>";
        header('Location: index.php');
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
    <title>BFP Taguig</title>
    <link rel="icon" href="img/page-favicon.svg" type="image/x-icon">
    <link href="//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="css/loginform.css" type="text/css" media="all" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="../vendor/jquery/jquery.min.js"></script>
</head>

<body>
    <section class="w3l-mockup-form">
        <div class="container">
            <div class="main-mockup">
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
                        <input type="text" class="email" name="user" placeholder="Enter Username or Email" required>
                        <div class="login_input_pass">
                            <input type="password" id="password" class="password" name="password"
                                placeholder="Enter Your Password" style="margin-bottom: 2px;" required>
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <div class="forg_pass">
                            <p><a href="forgot-password.php">Forgot Password?</a></p>
                        </div>
                        <div class="google-captcha">
                            <div class="g-recaptcha" data-sitekey="6Lc489wpAAAAALErVt_h0RVHokOAM_HGMoLdk2j_"></div>
                        </div>
                        <button name="login_acc_btn" class="btn" type="submit">Login</button>
                    </form>

                </div>
            </div>
        </div>
    </section>
    <script src="../js/script.js"></script>
    <script>
    const passwordInput = document.getElementById("password");
    const eyeIcon = document.querySelector('i');
    eyeIcon.addEventListener("click", () => {
        passwordInput.type = passwordInput.type === "password" ? "text" : "password";
        eyeIcon.className = `fa-solid fa-eye${passwordInput.type === "password" ? "" : "-slash"}`;
    });
    </script>
</body>

</html>
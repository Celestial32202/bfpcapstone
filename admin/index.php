<?php
require_once 'auth.php';
require_once 'server/jwt_handler.php';
redirectIfLoggedIn();
// If the user is already logged in, redirect them to the dashboard

if (isset($_POST['login_acc_btn'])) {
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $recaptchaSuccess = verifyRecaptcha($recaptchaResponse);
    if ($recaptchaSuccess) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $query = "SELECT id, password, admin_position, admin_permissions, last_name, branch FROM admin_creds WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $fetch = $result->fetch_assoc();
            $fetch_pass = $fetch['password'];
            if (password_verify($password, $fetch['password'])) {
                // Generate a new session ID
                function generateUniqueSessionId($conn, $email)
                {
                    $count = 0;
                    do {
                        // Regenerate session ID
                        session_regenerate_id(true);
                        $new_session_id = session_id();

                        // Check if this session ID already exists in the database
                        $stmt = $conn->prepare("SELECT COUNT(*) FROM admin_creds WHERE session_id = ?");
                        $stmt->bind_param("s", $new_session_id);
                        $stmt->execute();
                        $stmt->bind_result($count);
                        $stmt->fetch();
                        $stmt->close();
                    } while ($count > 0); // Keep generating until a unique session ID is found

                    // Update the database to store the new session ID
                    $stmt = $conn->prepare("UPDATE admin_creds SET session_id = ? WHERE email = ?");
                    $stmt->bind_param("ss", $new_session_id, $email);
                    $stmt->execute();
                    $stmt->close();

                    return $new_session_id;
                }
                // Store user session
                $_SESSION['branch'] = $fetch['branch'];
                $_SESSION['admin_user'] = $fetch['last_name'];
                $_SESSION['ADMIN_SESSION'] = $email;
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
                $_SESSION['errors'] = "<div class='alert alert-danger'>Incorrect email or password!</div>";
                header("Location: index.php");
                exit();
            }
        } else {
            $_SESSION['errors'] = "<div class='alert alert-danger'>Incorrect email or password!</div>";
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
    <title>SKM</title>
    <link rel="icon" href="../images/proper-logo.jpg" type="image/x-icon">
    <link href="//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="css/loginform.css" type="text/css" media="all" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="vendor/jquery/jquery.min.js"></script>
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
                        <input type="email" class="email" name="email" placeholder="Enter Your Email" required>
                        <div class="login_input_pass">
                            <input type="password" id="password" class="password" name="password"
                                placeholder="Enter Your Password" style="margin-bottom: 2px;" required>
                            <i class="fa-solid fa-eye"></i>
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
    <!-- <script src="../js/script.js"></script> -->
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
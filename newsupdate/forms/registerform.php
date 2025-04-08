<?php
require_once('../config.php');
include '../functions/email-sender.php';
session_start();

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// if (isset($_SESSION['SESSION_EMAIL'])) {
//     header('Location: index.php');
//     exit();
// }
if (isset($_POST['submit_form'])) {

    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $recaptchaSuccess = verifyRecaptcha($recaptchaResponse);
    if ($recaptchaSuccess) {
        $first_name = htmlspecialchars($_POST['first_name']);
        $middle_name = htmlspecialchars($_POST['middle_name']);
        $last_name = htmlspecialchars($_POST['last_name']);
        $birth_date = htmlspecialchars($_POST['birthdate']);
        $email = htmlspecialchars($_POST['email']);
        $mb_number = htmlspecialchars($_POST['mb_number']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm-password'];

        if (!preg_match('/^09\d{9}$/', $mb_number)) {
            $_SESSION['errors'] = "<div class='alert alert-danger'>Invalid mobile number format.</div>";
            header('Location: registerform.php');
            die();
        }
        $checkEmailQuery = "SELECT * FROM users_creds WHERE email = ?";
        $stmt = $conn->prepare($checkEmailQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $Email_check_rslt = $stmt->get_result();

        if ($Email_check_rslt->num_rows > 0) {
            $_SESSION['errors'] = "<div class='alert alert-danger'>This email address already exists.</div>";
            header('Location: registerform.php');
            die();
        } else {
            if ($password == $confirm_password) {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $verif_code = rand(999999, 111111);
                $verif_token = bin2hex(random_bytes(16));
                $status = "notverified";

                $insertQuery = "INSERT INTO users_creds (first_name, middle_name, last_name, birth_date, email, 
                                phone_number, hashed_password, verif_code, verif_token, acc_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param(
                    "ssssssssss",
                    $first_name,
                    $middle_name,
                    $last_name,
                    $birth_date,
                    $email,
                    $mb_number,
                    $hashed_password,
                    $verif_code,
                    $verif_token,
                    $status
                );
                $stmt->execute();
                if ($stmt->affected_rows > 0) {
                    if (sendVerificationEmail($email, $verif_code, $verif_token, $first_name)) {
                        // If the email was sent successfully, insert into the database
                        $_SESSION['info'] = "<div class='alert alert-info'>We've sent a verification code to your email - $email</div>";
                        $_SESSION['verify_email_reg'] = $email;
                        header('Location: verification-form.php');
                        die();
                    } else {
                        $_SESSION['errors'] = "<div class='alert alert-danger'>Message could not be sent. Please try again later.</div>";
                        header('Location: registerform.php');
                        die();
                    }
                } else {
                    $_SESSION['errors'] = "<div class='alert alert-danger'>Something went wrong.</div>";
                    header('Location: registerform.php');
                    die();
                }
            } else {
                $_SESSION['errors'] = "<div class='alert alert-danger'>Confirm password didn't match</div>";
                header('Location: registerform.php');
                die();
            }
        }
    } else {
        $_SESSION['errors'] = "<div class='alert alert-danger'>reCAPTCHA verification failed. Please try again.</div>";
        header('Location: registerform.php');
        die();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password']) && !isset($_POST['submit_form'])) {
    $password = $_POST['password'];
    $requirements = validatePasswordRequirements($password);
    echo json_encode(['requirements' => array_values($requirements)]);
    exit;
}
function validatePasswordRequirements($password)
{
    $requirements = [
        'length' => strlen($password) >= 8,
        'number' => preg_match('/\d/', $password),
        'lowercase' => preg_match('/[a-z]/', $password),
        'special' => preg_match('/[!$%^&*()\-_=+{};:,.#~`\[\]\\\|"\',?\/@<>]/', $password),
        'uppercase' => preg_match('/[A-Z]/', $password)
    ];
    return $requirements;
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
        <div class="reg-container">
            <div class="workinghny-form-grid">
                <div class="main-mockup">
                    <div class="alert-close">
                        <span class="fa fa-close"></span>
                    </div>
                    <div class="content-wthree">
                        <h2>Sign Up</h2>
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
                        <form id="register_form" name="register_form" method="post">
                            <div class="reg-input-form">
                                <input type="text" class="name" name="first_name" placeholder="First Name"
                                    value="<?php echo isset($_POST['submit_form']) ? htmlspecialchars($_POST['first_name']) : ''; ?>"
                                    required>
                                <input type="text" class="name" name="middle_name" placeholder="Middle Name"
                                    value="<?php echo isset($_POST['submit_form']) ? htmlspecialchars($_POST['middle_name']) : ''; ?>"
                                    required>
                            </div>
                            <div class="reg-input-form">
                                <input type="text" class="name" name="last_name" placeholder="Last Name"
                                    value="<?php echo isset($_POST['submit_form']) ? htmlspecialchars($_POST['last_name']) : ''; ?>"
                                    required>
                                <input class="name" type="date" id="birthdate" name="birthdate" required
                                    min="1905-01-01" max="" onkeydown="return false;" onclick="showCalendar()"
                                    value="<?php echo isset($_POST['submit_form']) ? htmlspecialchars($_POST['birth_date']) : ''; ?>">
                            </div>
                            <input type="email" class="email" name="email" placeholder="Enter Your Email"
                                value="<?php echo isset($_POST['submit_form']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                required>
                            <input type="text" class="mb_number" name="mb_number" placeholder="Enter Your Mobile Number"
                                pattern="09\d{9}" oninput="limitInput(this, 11)"
                                onkeypress="return restrictInput(event)"
                                value="<?php echo isset($_POST['mb_number']) ? htmlspecialchars($_POST['mb_number']) : ''; ?>"
                                required>


                            <div id="overlay" class="overlay"></div>
                            <div class="pass_container">
                                <div class="first_input_pass">
                                    <input type="password" id="password" class="password" name="password"
                                        placeholder="Enter Your Password" onclick="showHiddenClass()" required />
                                    <i class="fa-solid fa-eye hidden"></i>
                                </div>
                                <div id="password-guidance" class="guidance-text">
                                    <div class="guidance-arrow"></div>
                                    <div id="pass_strngth_list" class="pass_strngth_list hidden">
                                        <span>Password Strength</span>
                                        <ul class="requirement-list">
                                            <li>
                                                <i class="fa-solid fa-circle"></i>
                                                <span>At least 8 characters length</span>
                                            </li>
                                            <li>
                                                <i class="fa-solid fa-circle"></i>
                                                <span>At least 1 number (0...9)</span>
                                            </li>
                                            <li>
                                                <i class="fa-solid fa-circle"></i>
                                                <span>At least 1 lowercase letter (a...z)</span>
                                            </li>
                                            <li>
                                                <i class="fa-solid fa-circle"></i>
                                                <span>At least 1 special symbol (!...$)</span>
                                            </li>
                                            <li>
                                                <i class="fa-solid fa-circle"></i>
                                                <span>At least 1 uppercase letter (A...Z)</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <input type="password" id="confirm-password" class="confirm-password"
                                    name="confirm-password" placeholder="Enter Your Confirm Password"
                                    onclick="showHiddenClass_2()" ; required disabled />
                                <div class="pass_matching">
                                    <i class="fas fa-exclamation-circle not_match"></i>
                                    <span class="match_or_not">Confirm your Password</span>
                                </div>
                            </div>
                            <div class="terms-container" style="padding-bottom: 40px;">
                                <input type="checkbox" id="terms" name="terms" required>
                                <label for="terms">I accept the <a href="../terms-condi.php" target="_blank">Terms and
                                        Conditions</a></label>
                            </div>
                            <div class="g-recaptcha " data-sitekey="6Lc489wpAAAAALErVt_h0RVHokOAM_HGMoLdk2j_"
                                style="margin-bottom:10px;"></div>
                            <button name="submit_form" class="btn" type="submit_form" disabled>Register</button>
                        </form>
                        <div class="social-icons">
                            <p>Have an account! <a href="loginform.php">Login</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="../js/script.js"></script>
    <?php include('../includes/reg-script.php') ?>
</body>

</html>
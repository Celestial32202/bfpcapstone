<?php
session_start();
require_once '../../config.php'; // Ensures database connection file is included only once
require_once '../configs/jwt_handler.php';

if (!isset($_GET['token'])) {
    $_SESSION['errors'] = "<div class='alert alert-danger'>No token provided.</div>";
    header("Location: ../index.php");
    exit();
}

$token = $_GET['token'];

// Decode the token
$decoded = JWTHandler::decodeForgotResetToken($token, $conn);

if (!$decoded || !isset($decoded['email'])) {
    $_SESSION['errors'] = "<div class='alert alert-danger'>Invalid or expired token.</div>";
    header("Location: ../index.php");
    exit();
}
$email = $decoded['email'];

if (isset($_POST['change_password'])) {
    try {
        // Get user input
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $cpassword = mysqli_real_escape_string($conn, $_POST['confirm-password']);

        // Check if new password matches confirm password
        if ($password !== $cpassword) {
            $_SESSION['errors'] = "<div class='alert alert-danger'>Confirm password does not match.</div>";
            header("Location: reset-password.php?token=" . urlencode($token));
            exit();
        }

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE admin_creds SET password = ? WHERE email = ?");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }

        $stmt->bind_param("ss", $hashed_password, $email);
        if (!$stmt->execute()) {
            throw new Exception("Query execution failed: " . $stmt->error);
        }
        $stmt->close();



        $_SESSION['info'] = "<div class='alert alert-success'>Your password has been changed. Now you can log in with your new password.</div>";
        header("Location: ../index.php");
        exit();
    } catch (Exception $e) {
        // Log error message to a file instead of showing it to users
        error_log($e->getMessage(), 3, '../../logs/error_log.txt');

        $_SESSION['errors'] = "<div class='alert alert-danger'>Something went wrong. Please try again later.</div>";
        header("Location: reset-password.php?token=" . urlencode($token));
        exit();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password']) && !isset($_POST['change_password'])) {
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
?>
<!DOCTYPE html>

<head>
    <title>BFP Taguig</title>
    <link rel="icon" href="img/page-favicon.svg" type="image/x-icon">
    <link href="//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
                        <h2>Change Password</h2>
                        <p>Use new unique password </p>
                        <?php
                        if (isset($_SESSION['errors'])) {
                            echo $_SESSION['errors'];
                            unset($_SESSION['errors']);
                        }
                        ?>
                        <form action="" method="post">
                            <div class="pass_container">

                                <div class="first_input_pass">
                                    <input type="password" id="password" class="password" name="password"
                                        placeholder="Enter Your Password" onclick="showHiddenClass()" required />
                                    <i class="fa-solid fa-eye hidden"></i>
                                </div>
                                <input type="password" id="confirm-password" class="confirm-password"
                                    name="confirm-password" placeholder="Enter Your Confirm Password"
                                    onclick="showHiddenClass_2()" required disabled />
                                <div class="pass_matching">
                                    <i class="fas fa-exclamation-circle not_match"></i>
                                    <span class="match_or_not">Confirm your Password</span>
                                </div>
                                <div id="pass_strngth_list" class="">
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
                                <button name="change_password" class="btn" type="submit" disabled>Change
                                    Password</button>
                            </div>
                        </form>
                        <div class="social-icons">
                            <p>Back to! <a href="loginform.php">Login</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script>
    $(document).ready(function(c) {
        $('.alert-close').on('click', function(c) {
            $('.main-mockup').fadeOut('slow', function(c) {
                $('.main-mockup').remove();
            });
        });
    });
    const passwordInput = document.getElementById("password");
    const passwordInput_2nd = document.getElementById("confirm-password");
    const requirementList = document.querySelectorAll(".requirement-list li");
    const eyeIcon = document.querySelector('i');
    const hidden_Strngth_List = document.getElementById('pass_strngth_list');
    const reg_btn = document.querySelector('.btn');
    const not_match = document.querySelector(".not_match");
    const match_or_not = document.querySelector(".match_or_not");
    const updateIndicators = (requirements) => {
        requirements.forEach((requirement, index) => {
            const requirementItem = requirementList[index];
            if (requirement) {
                requirementItem.classList.add("valid");
                requirementItem.firstElementChild.className = "fa-solid fa-check";
            } else {
                requirementItem.classList.remove("valid");
                requirementItem.firstElementChild.className = "fa-solid fa-circle";
            }
        });
    };
    passwordInput.addEventListener("input", () => {
        const password = passwordInput.value;

        // Send an AJAX request to the server-side script
        const xhr = new XMLHttpRequest();
        xhr.open("POST", window.location.href, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                const requirements = response.requirements;
                updateIndicators(requirements);

                const allRequirementsMet = Object.values(requirements).every((requirement) => requirement);
                if (allRequirementsMet) {
                    passwordInput_2nd.removeAttribute("disabled");
                } else {
                    passwordInput_2nd.setAttribute("disabled", "disabled");
                }
            }
        };
        xhr.send("password=" + encodeURIComponent(password));
    });
    passwordInput_2nd.addEventListener("input", () => {
        if (passwordInput_2nd.value.trim() === '') {
            match_or_not.innerText = "Confirm your password";
            match_or_not.style.color = "#a6a6a6";
            not_match.style.display = "block";
            not_match.style.color = "#a6a6a6";
        } else if (passwordInput.value !== passwordInput_2nd.value) {
            match_or_not.innerText = "Password didn't matched";
            not_match.style.display = "block";
            not_match.style.color = "#D93025";
            match_or_not.style.color = "#D93025";
            reg_btn.setAttribute("disabled", "disabled");
        } else if (passwordInput.value === passwordInput_2nd.value) {
            match_or_not.innerText = "Password matched";
            not_match.style.display = "none";
            match_or_not.style.color = "#4070F4";
            reg_btn.removeAttribute("disabled");
        }
    });


    function showHiddenClass() {
        hidden_Strngth_List.classList.remove('hidden');
        eyeIcon.classList.remove('hidden');
        if (passwordInput_2nd.value.trim() !== '') {
            passwordInput_2nd.value = '';
            not_match.style.display = "none";
            match_or_not.style.display = "none";
            match_or_not.innerText = "Confirm your password";
            match_or_not.style.color = "#a6a6a6";
            not_match.style.color = "#a6a6a6";
            reg_btn.setAttribute("disabled", "disabled");
        } else if (passwordInput_2nd.value.trim() === '') {
            not_match.style.display = "none";
            match_or_not.style.display = "none";
        }
    }

    function showHiddenClass_2() {
        hidden_Strngth_List.classList.add('hidden');
        if (passwordInput_2nd.value.trim() === '') {
            not_match.style.display = "block";
            match_or_not.style.display = "block";
        }
    }
    eyeIcon.addEventListener("click", () => {
        passwordInput.type = passwordInput.type === "password" ? "text" : "password";
        passwordInput_2nd.type = passwordInput_2nd.type === "password" ? "text" : "password";
        eyeIcon.className = `fa-solid fa-eye${passwordInput.type === "password" ? "" : "-slash"}`;
    });
    document.addEventListener('click', function(event) {
        const click_Target = event.target;
        if (click_Target !== passwordInput &&
            click_Target !== eyeIcon &&
            !hidden_Strngth_List.contains(click_Target)) {
            if (passwordInput.value.trim() === '') {
                eyeIcon.classList.add('hidden');;
            }
        }
    });
    </script>
</body>

</html>
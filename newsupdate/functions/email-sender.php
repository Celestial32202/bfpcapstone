<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

// Initialize the $mail variable globally
$mail = initializeMailer();

function initializeMailer() {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'skmagtanggol123@gmail.com';
    $mail->Password   = 'amvq hjdn gkkw uici';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->setFrom('skmagtanggol123@gmail.com');
    return $mail;
}

// function sendVerificationEmail($to, $code, $token) {
//     global $mail; // Use the global $mail variable
//     try {    
//         $mail->addAddress($to);
//         $mail->isHTML(true);
//         $mail->Subject = 'no reply';
//         $mail->Body    = 'Your verification code is: ' . $code;
//         $mail->Body    = 'Or Click the following button below to verify your email: ';
//         $mail->Body    = 'http://yourdomain.com/verify.php?token=' . $token;
//         $mail->send();
//         return true;
//     } catch (Exception $e) {
//         return false;
//     }
// }

function sendVerificationEmail($to, $code, $token, $first_name) {
    global $mail; // Use the global $mail variable
    try {    
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'no reply';

        // Load the external template content
        $template = file_get_contents('../functions/email-template.html');

        // Replace placeholders with actual values
        $template = str_replace('{{verification_code}}', $code, $template);
        $template = str_replace('{{email}}', $to, $template);
        $template = str_replace('{{first_name}}', $first_name, $template);
        $template = str_replace('{{verification_link}}', 'http://localhost/Baranggay%20Magtanggol/LATEST-051724/email-verify.php?token=' . $token, $template);

        // Set the email body as the modified template
        $mail->Body = $template;

        // Send the email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function send_otp_code_email($conn, $email) {
    global $mail; // Use the global $   mail variable
    $code = rand(999999, 111111);
    $insert_code = "UPDATE users_creds SET verif_code = ? WHERE email = ?";
                $insert_statement = mysqli_prepare($conn, $insert_code);
                if ($insert_statement) {
                    mysqli_stmt_bind_param($insert_statement, "ss", $code, $email);
                    mysqli_stmt_execute($insert_statement);
                    try {
                        global $mail;
                        $mail->clearAddresses(); 
                        $mail->addAddress($email);
                        $mail->isHTML(true);
                        $mail->Subject = 'no reply';
                        $mail->Body    = 'Your verification code is: ' . $code;
                        $mail->send();
                        return;
                    } catch (Exception $e) {
                        $errors = "<div class='alert alert-danger'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</div>";
                    }
                } else {
                    $errors = "<div class='alert alert-danger'>Failed to prepare the database query.</div>";
    }
    // try {
    //     $mail->clearAddresses();
    //     $mail->addAddress($email);
    //     $mail->isHTML(true);
    //     $mail->Subject = 'no reply';
    //     $mail->Body    = 'Your verification code is: ' . $code;
    //     $mail->send();

    //     $updateCodeQuery = "UPDATE users_creds SET verif_code = ? WHERE email = ?";
    //     $updateCodeStatement = mysqli_prepare($conn, $updateCodeQuery);

    //     if ($updateCodeStatement) {
    //         mysqli_stmt_bind_param($updateCodeStatement, "is", $code, $email);
    //         mysqli_stmt_execute($updateCodeStatement);
    //     } else {
    //         $errors = "<div class='alert alert-danger'>Failed to prepare the database query.</div>";
    //         return;
    //     }
    // } catch (Exception $e) {
    //     $errors = "<div class='alert alert-danger'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</div>";
    //     return;
    // }
}
    
    function send_forg_pass_email($conn, $email) {
        $check_email = "SELECT * FROM users_creds WHERE email=?";
        $statement = mysqli_prepare($conn, $check_email);
        
        if ($statement) {
            mysqli_stmt_bind_param($statement, "s", $email);
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);
    
            if (mysqli_num_rows($result) > 0) {
                $code = rand(999999, 111111);
                $insert_code = "UPDATE users_creds SET verif_code = ? WHERE email = ?";
                $insert_statement = mysqli_prepare($conn, $insert_code);
    
                if ($insert_statement) {
                    mysqli_stmt_bind_param($insert_statement, "ss", $code, $email);
                    mysqli_stmt_execute($insert_statement);
    
                    try {
                        global $mail;
                        $mail->clearAddresses(); 
                        $mail->addAddress($email);
                        $mail->isHTML(true);
                        $mail->Subject = 'no reply';
                        $mail->Body    = 'Your password reset code is: ' . $code;
                        $mail->send();
                        return;
                    } catch (Exception $e) {
                        $errors = "<div class='alert alert-danger'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</div>";
                    }
                } else {
                    $errors = "<div class='alert alert-danger'>Failed to prepare the database query.</div>";
                }
    
                mysqli_stmt_close($insert_statement);
            } else {
                $errors = "<div class='alert alert-danger'>This email address isn't registered yet!</div>";
            }
    
            mysqli_stmt_close($statement);
        } else {
            $errors = "<div class='alert alert-danger'>Failed to prepare the database query.</div>";
        }
    }
?>

<?php
session_start();
require_once 'email-sender.php';
require_once '../../config.php'; // Ensures database connection file is included only once
require_once '../server/jwt_handler.php';
if (!isset($_SESSION['permissions']['manage_accounts']) && $_SESSION['permissions']['manage_accounts'] != 1) {
    header("Location: dashboard.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Sanitize and fetch form inputs
        $first_name = $conn->real_escape_string($_POST['first_name']);
        $last_name = $conn->real_escape_string($_POST['last_name']);
        $middle_name = $conn->real_escape_string($_POST['middle_name']);
        $email = $conn->real_escape_string($_POST['email']);
        $mb_number = $conn->real_escape_string($_POST['mb_number']);
        $admin_pos = $conn->real_escape_string($_POST['position']);
        $branch = $conn->real_escape_string($_POST['branch']);

        // Modify branch based on position
        if ($admin_pos !== "Fire Officer" && $admin_pos !== "Fire Officer Supervisor") {
            $branch = $admin_pos; // Set branch to the same value as position
        }
        $password = $conn->real_escape_string($_POST['password']);
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(6));
        function generateAdminId($conn)
        {
            $count = 0;
            do {
                $eightRandomNumbers = str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
                $admin_id = "BFPT-$eightRandomNumbers";

                // Check if the generated ID already exists in the database
                $stmt = $conn->prepare("SELECT COUNT(*) FROM admin_creds WHERE admin_id = ?");
                $stmt->bind_param("s", $admin_id);
                $stmt->execute();
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();
            } while ($count > 0); // Repeat until a unique ID is found

            return $admin_id;
        }

        // Example usage (assuming $conn is your database connection)
        $admin_id = generateAdminId($conn);

        // Assign permissions based on position
        $permissions = [];
        switch ($admin_pos) {
            case 'Command Officer Head':
                $permissions = ["main_dashboard" => 1, "manage_accounts" => 1, "edit_accounts" => 1, "manage_reports" => 1, "monitor_rescue" => 1];
                break;
            case 'Command Officer Staff':
                $permissions = ["main_dashboard" => 1, "manage_accounts" => 1, "manage_reports" => 1, "monitor_rescue" => 1];
                break;
            case 'Fire Officer Supervisor':
                $permissions = ["main_dashboard" => 1, "submit_accounts" => 1, "recieve_rescue_reports" => 1];
                break;
            case 'Fire Officer':
                $permissions = ["main_dashboard" => 1, "recieve_rescue_reports" => 1];
                break;
            default:
                throw new Exception("Invalid position selected");
        }

        // Convert permissions to JSON
        $permissions_json = json_encode($permissions);

        // Check if email already exists
        $stmt = $conn->prepare("SELECT email FROM admin_creds WHERE email = ?");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        if (!$stmt->execute()) {
            throw new Exception("Query execution failed: " . $stmt->error);
        }
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['error_message'] = "<div class='mt-3 alert alert-warning'>Error: Email already exists!</div>";
            header("Location: ../add-admin-acc.php");
            exit();
        }

        // Insert new admin credentials
        $stmt = $conn->prepare("INSERT INTO admin_creds (admin_id,
                                                        first_name, 
                                                        middle_name, 
                                                        last_name, 
                                                        email, 
                                                        contact_number, 
                                                        admin_position,
                                                        branch, 
                                                        password, 
                                                        admin_permissions) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        $stmt->bind_param("ssssssssss", $admin_id, $first_name, $middle_name, $last_name, $email, $mb_number, $admin_pos, $branch, $hashed_password, $permissions_json);
        if (!$stmt->execute()) {
            throw new Exception("Error inserting new admin account: " . $stmt->error);
        }

        // Generate a unique reset token
        do {
            $token = JWTHandler::encodeResetToken($email, 3600); // 1-hour expiration

            // Check if the token exists in the blacklist
            $stmt = $conn->prepare("SELECT token FROM token_blacklist WHERE token = ?");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }
            $stmt->bind_param("s", $token);
            if (!$stmt->execute()) {
                throw new Exception("Query execution failed: " . $stmt->error);
            }
            $stmt->store_result();

            $exists = $stmt->num_rows > 0;

            // If the token exists, remove it from blacklist
            if ($exists) {
                $stmt = $conn->prepare("DELETE FROM token_blacklist WHERE token = ?");
                if (!$stmt) {
                    throw new Exception("Prepare statement failed: " . $conn->error);
                }
                $stmt->bind_param("s", $token);
                if (!$stmt->execute()) {
                    throw new Exception("Error removing old token: " . $stmt->error);
                }
            }
        } while ($exists);

        // Send email verification
        if (sendAccountVerificationEmail($email, $first_name, $password, $token)) {
            $_SESSION['error_message'] = "<div class='mt-3 alert alert-success'>Account Added!</div>";
        } else {
            $_SESSION['error_message'] = "<div class='mt-3 alert alert-warning'>Error: Account created, but email not sent.</div>";
        }

        header("Location: ../add-admin-acc.php");
        exit();
    } catch (Exception $e) {
        // Log the error message to a file
        error_log("[" . date("Y-m-d H:i:s") . "] ERROR: " . $e->getMessage() . PHP_EOL, 3, '../logs/error_log.txt');


        $_SESSION['error_message'] = "<div class='mt-3 alert alert-danger'>Something went wrong. Please try again later.</div>";
        header("Location: ../add-admin-acc.php");
        exit();
    } finally {
        // Close the database connection if it exists
        if (isset($conn)) {
            $conn->close();
        }
    }
}
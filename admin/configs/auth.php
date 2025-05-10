<?php
session_start();
require_once('../config.php');

function checkLogin()
{
    if (!isset($_SESSION['ADMIN_SESSION']) && !isset($_SESSION['admin_user']) && !isset($_SESSION['branch']) && !isset($_SESSION['SESSION_ID'])) {
        header("Location: index.php");
        exit();
    }
    // Validate session ID to prevent multiple logins
    global $conn;
    $email = $_SESSION['ADMIN_SESSION'];

    $query = "SELECT session_id FROM admin_creds WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        // If session IDs don't match, log out the user
        if ($row['session_id'] !== $_SESSION['SESSION_ID']) {
            session_destroy();
            header("Location: index.php?session_expired=1");
            exit();
        }
    } else {
        session_destroy();
        header("Location: index.php");
        exit();
    }
}

function redirectIfLoggedIn()
{
    if (isset($_SESSION['ADMIN_SESSION'])) {
        header("Location: dashboard.php");
        exit();
    }
}

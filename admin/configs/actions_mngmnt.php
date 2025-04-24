<?php
session_start();
include('../../config.php'); // <- adjust path if needed

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $user_id = trim($_POST['user_id']); // simple cleanup


    if ($action == 'delete') {
        $stmt = $conn->prepare("UPDATE admin_creds SET is_deleted = 1 WHERE admin_id = ?");
        $stmt->bind_param("s", $user_id);
    } elseif ($action == 'undelete') {
        $stmt = $conn->prepare("UPDATE admin_creds SET is_deleted = 0 WHERE admin_id = ?");
        $stmt->bind_param("s", $user_id);
    } elseif ($action == 'lock') {
        $stmt = $conn->prepare("UPDATE admin_creds SET is_locked = 1 WHERE admin_id = ?");
        $stmt->bind_param("s", $user_id);
    } elseif ($action == 'unlock') {
        $stmt = $conn->prepare("UPDATE admin_creds SET is_locked = 0 WHERE admin_id = ?");
        $stmt->bind_param("s", $user_id);
    } else {
        echo "invalid_action";
        exit();
    }

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
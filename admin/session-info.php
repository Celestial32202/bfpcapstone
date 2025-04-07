<?php
session_start();
header('Content-Type: application/json');
// Check if the session variable exists
if (!isset($_SESSION['admin_user'])) {
    echo json_encode(['error' => 'No admin session found']);
    exit;
}


echo json_encode([
    'session_id' => $_SESSION['SESSION_ID'],
    'admin_user' => $_SESSION['admin_user'],
    'admin_position' => $_SESSION['position'],
    'admin_branch' => $_SESSION['branch'],
    'admin_email' => $_SESSION['ADMIN_SESSION']
]);
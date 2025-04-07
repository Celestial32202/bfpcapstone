<?php
session_start();
include '../../config.php';
include '../server/jwt_handler.php'; // Include the JWT handler class

// Get the incident token (for example, passed as a query parameter or POST data)
$incidentToken = $_GET['incidentToken'] ?? null; // Adjust this based on how the token is passed

if (!$incidentToken) {
    die(json_encode(["status" => "error", "message" => "❌ Incident token is required."]));
}

// Decode the incident token using JWTHandler
$decodedData = JWTHandler::incident_decode($incidentToken);

// Check if decoding was successful
if ($decodedData) {
    // Get the admin's session data (already stored in session)
    $adminUser = $_SESSION['admin_user'] ?? null;
    $adminBranch = $_SESSION['branch'] ?? null;

    if (!$adminUser || !$adminBranch) {
        die(json_encode(["status" => "error", "message" => "❌ Admin session data is missing."]));
    }

    // Read JSON input data from the body of the request
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true); // Decode JSON to an array

    // Check if userStatus is provided in the request
    if (!isset($data['userStatus'])) {
        die(json_encode(["status" => "error", "message" => "❌ User status is required."]));
    }

    // Get user status from the decoded JSON
    $userStatus = $data['userStatus'];

    // Extract incident_id from decoded token
    $incidentId = $decodedData['incident_id'];

    if (!$incidentId) {
        die(json_encode(["status" => "error", "message" => "❌ Incident ID is missing in the token."]));
    }

    // Secure the values (escaping strings for SQL query)
    $incidentId = mysqli_real_escape_string($conn, $incidentId);
    $adminUser = mysqli_real_escape_string($conn, $adminUser);
    $adminBranch = mysqli_real_escape_string($conn, $adminBranch);
    $userStatus = mysqli_real_escape_string($conn, $userStatus);

    // Update query for accepted_fire_rescues table
    $updateQuery = "UPDATE accepted_fire_rescues 
                    SET status = '$userStatus'
                    WHERE incident_id = '$incidentId' AND fire_officer = '$adminUser' AND branch = '$adminBranch'";

    // Execute the query
    if (mysqli_query($conn, $updateQuery)) {
        echo json_encode(["status" => "success", "message" => "✅ Rescue status updated successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "❌ Error updating rescue status: " . mysqli_error($conn)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "❌ Failed to decode incident token."]);
}
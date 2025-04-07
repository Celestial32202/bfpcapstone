<?php
session_start();
header('Content-Type: application/json');
include '../../config.php'; // Ensure this file connects to your database
include '../server/jwt_handler.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_user'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$adminUser = $_SESSION['admin_user']; // Fire officer's username
$adminBranch = $_SESSION['branch'];
$incidentToken = $data['incidentToken'] ?? null;
$latitude = $data['latitude'] ?? null;
$longitude = $data['longitude'] ?? null;
if (!$incidentToken) {
    echo json_encode(["error" => "Incident token is missing"]);
    exit;
}

// Decode token to get incident_id
$decodedData = JWTHandler::incident_decode($incidentToken);
if (!$decodedData || !isset($decodedData['incident_id'])) {
    echo json_encode(["error" => "Invalid token"]);
    exit;
}

$incidentId = $decodedData['incident_id'];

// Fetch rescue_details_id using incident_id
$stmt = $conn->prepare("SELECT id FROM rescue_details WHERE incident_id = ?");
if (!$stmt) {
    echo json_encode(["error" => "SQL preparation failed", "sql_error" => $conn->error]);
    exit;
}

$stmt->bind_param("s", $incidentId);
$stmt->execute();
$result = $stmt->get_result();
$rescueDetails = $result->fetch_assoc();

if (!$rescueDetails) {
    echo json_encode(["error" => "Rescue details not found"]);
    exit;
}

$rescueDetailsId = $rescueDetails['id'];

// Check if there's already an accepted entry
$checkStmt = $conn->prepare("SELECT id, status, branch, fire_officer FROM accepted_fire_rescues WHERE rescue_details_id = ? AND fire_officer = ?");
if (!$checkStmt) {
    echo json_encode(["error" => "SQL preparation failed", "sql_error" => $conn->error]);
    exit;
}
$checkStmt->bind_param("is", $rescueDetailsId, $adminUser);
$checkStmt->execute();
$checkStmt->store_result();
if ($checkStmt->num_rows > 0) {
    // Rescue already accepted, check if it's ongoing
    $checkStmt->bind_result($acceptedRescueId, $status, $branch, $fireOfficer);
    $checkStmt->fetch();
    // Check if the branch and fire_officer match the session values
    if ($branch !== $adminBranch || $fireOfficer !== $adminUser) {
        echo json_encode(["error" => "You are not authorized to update this rescue"]);
        exit;
    }
    if ($status === 'ongoing') {
        // If the rescue is ongoing, do not update location
        echo json_encode([
            "success" => "Rescue is already ongoing. Location cannot be updated.",
            "status" => "ongoing"
        ]);
        exit;
    } elseif ($status === 'returning') {
        echo json_encode([
            "success" => "Rescue is already returning..",
            "status" => "returning"
        ]);
    } elseif ($status === 'returned') {
        echo json_encode([
            "success" => "Rescue is already returning..",
            "status" => "returned"
        ]);
    } elseif ($status === 'arrived') {
        // Fetch lat/lng of this accepted rescue
        $locationStmt = $conn->prepare("SELECT latitude, longitude FROM accepted_fire_rescues WHERE id = ?");
        if (!$locationStmt) {
            echo json_encode(["error" => "SQL preparation failed", "sql_error" => $conn->error]);
            exit;
        }

        $locationStmt->bind_param("i", $acceptedRescueId);
        $locationStmt->execute();
        $locationResult = $locationStmt->get_result();
        $location = $locationResult->fetch_assoc();

        if ($location) {
            echo json_encode([
                "success" => "Rescue has arrived",
                "status" => "arrived",
                "latitude" => $location['latitude'],
                "longitude" => $location['longitude']
            ]);
        } else {
            echo json_encode([
                "error" => "Coordinates not found for arrived rescue"
            ]);
        }

        $locationStmt->close();
    } else {
        // Already accepted — update location and status
        $updateStmt = $conn->prepare("UPDATE accepted_fire_rescues SET latitude = ?, longitude = ?, status = 'ongoing' WHERE id = ? AND rescue_details_id = ? AND fire_officer = ? AND branch = ? AND incident_id = ?");
        if (!$updateStmt) {
            echo json_encode(["error" => "SQL preparation failed", "sql_error" => $conn->error]);
            exit;
        }
        $updateStmt->bind_param("ddiisss", $latitude, $longitude, $acceptedRescueId, $rescueDetailsId, $adminUser, $adminBranch, $incidentId);

        if ($updateStmt->execute()) {
            echo json_encode(["success" => "Rescue location updated to ongoing"]);
        } else {
            echo json_encode(["error" => "Failed to update location", "sql_error" => $updateStmt->error]);
        }
        $updateStmt->close();
    }
} else {
    // Not accepted yet — insert as accepted
    $insertStmt = $conn->prepare("INSERT INTO accepted_fire_rescues (rescue_details_id, incident_id, fire_officer, branch, status, time_accepted) VALUES (?, ?, ?, ?, 'accepted', NOW())");
    if (!$insertStmt) {
        echo json_encode(["error" => "SQL preparation failed", "sql_error" => $conn->error]);
        exit;
    }
    $insertStmt->bind_param("isss", $rescueDetailsId, $incidentId, $adminUser, $adminBranch);

    if ($insertStmt->execute()) {
        echo json_encode(["success" => "Rescue accepted successfully"]);
    } else {
        echo json_encode(["error" => "Database insert failed", "sql_error" => $insertStmt->error]);
    }
    $insertStmt->close();
}
$checkStmt->close();
$stmt->close();
$conn->close();
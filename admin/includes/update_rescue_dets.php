<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('../../config.php');
require_once '../server/jwt_handler.php';

header('Content-Type: application/json');

try {
    // Validate Token
    $token = isset($_GET['token']) ? trim($_GET['token']) : '';
    if (!$token) {
        echo json_encode(['error' => 'No token provided.', 'step' => 'Token Validation']);
        exit;
    }

    $decoded = JWTHandler::decode($token);
    if (!$decoded || !isset($decoded->incident_id)) {
        echo json_encode(['error' => 'Invalid or expired token.', 'step' => 'Token Decoding']);
        exit;
    }

    $incident_id = htmlspecialchars($decoded->incident_id, ENT_QUOTES, 'UTF-8');

    // Check if rescue details already exist for this incident
    $stmt = $conn->prepare("SELECT COUNT(*) FROM rescue_details WHERE incident_id = ?");
    if (!$stmt) {
        echo json_encode(['error' => 'SQL preparation failed.', 'step' => 'Checking Existing Rescue', 'sql_error' => $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $incident_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode(['error' => 'Rescue details already dispatched for this incident.', 'step' => 'Checking Existing Rescue']);
        exit;
    }

    // Fetch Incident Details
    $stmt = $conn->prepare("SELECT incident_location, info_message FROM incident_report WHERE incident_id = ?");
    if (!$stmt) {
        echo json_encode(['error' => 'SQL preparation failed.', 'step' => 'Fetching Incident', 'sql_error' => $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $incident_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        echo json_encode(['error' => 'SQL execution failed.', 'step' => 'Executing Incident Query', 'sql_error' => $stmt->error]);
        exit;
    }

    if ($result->num_rows === 0) {
        echo json_encode(['error' => 'Incident not found.', 'step' => 'Fetching Incident']);
        exit;
    }

    $row = $result->fetch_assoc();
    $incident_location = htmlspecialchars($row['incident_location'], ENT_QUOTES, 'UTF-8');
    $info_message = htmlspecialchars($row['info_message'], ENT_QUOTES, 'UTF-8');

    // Read additional data sent from JavaScript
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);

    if (!$input) {
        echo json_encode(['error' => 'Invalid JSON data received.', 'step' => 'Parsing JSON Input']);
        exit;
    }

    $currentUserId = $input['userId'] ?? null;
    $latitude = $input['lat'] ?? null;
    $longitude = $input['lon'] ?? null;
    $selectedStations = $input['fireStations'] ?? [];

    if (!$currentUserId || !$latitude || !$longitude || empty($selectedStations)) {
        echo json_encode([
            'error' => 'Missing required fields.',
            'step' => 'Validating Input Data',
            'received_data' => $input
        ]);
        exit;
    }
    $generated_token = JWTHandler::incident_encode($incident_id, $latitude, $longitude);
    // Insert into `rescue_details`
    $stmt = $conn->prepare("INSERT INTO rescue_details 
                            (incident_id, sent_by, incident_location, info_message, status, latitude, longitude, auth_token, submitted_at) 
                            VALUES (?, ?, ?, ?, 'ongoing', ?, ?, ?, NOW())");

    if (!$stmt) {
        echo json_encode(['error' => 'SQL preparation failed.', 'step' => 'Preparing Rescue Insert', 'sql_error' => $conn->error]);
        exit;
    }

    $stmt->bind_param("ssssdds", $incident_id, $currentUserId, $incident_location, $info_message, $latitude, $longitude, $generated_token);

    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Database insert failed.', 'step' => 'Executing Rescue Insert', 'sql_error' => $stmt->error]);
        exit;
    }

    $rescueDetailsId = $stmt->insert_id; // Get the last inserted ID

    // Now insert the selected fire stations
    foreach ($selectedStations as $station) {
        $stmt2 = $conn->prepare("INSERT INTO rescue_selected_stations (rescue_details_id, fire_station_name, rescue_status) VALUES (?, ?, 'sent')");
        if (!$stmt2) {
            echo json_encode(['error' => 'SQL preparation failed for fire stations.', 'step' => 'Preparing Fire Stations Insert', 'sql_error' => $conn->error]);
            exit;
        }

        $station_name = $station['location'];
        $stmt2->bind_param("is", $rescueDetailsId, $station_name);

        if (!$stmt2->execute()) {
            echo json_encode(['error' => 'Database insert failed for fire stations.', 'step' => 'Executing Fire Stations Insert', 'sql_error' => $stmt2->error]);
            exit;
        }
        $stmt2->close();
    }
    echo json_encode([
        'success' => 'Rescue details updated successfully.',
        'incident_id' => $incident_id,
        'incident_location' => $incident_location,
        'info_message' => $info_message,
        'token' => $generated_token
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => 'An unexpected error occurred.', 'exception' => $e->getMessage()]);
}

// Close database connection
if (isset($stmt) && $stmt) {
    $stmt->close();
}
$conn->close();
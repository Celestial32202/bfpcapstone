<?php
include '../../config.php'; // Ensure this file connects to your database
include '../configs/jwt_handler.php';
header('Content-Type: application/json');
session_start();

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $decodedData = JWTHandler::incident_decode($token);

    if (!$decodedData) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid or expired token.']);
        exit;
    }

    // Extract decoded values
    $incident_id = $decodedData['incident_id'] ?? null;
    // latitude and longitude are already available from $decodedData
    // no need to fetch them from the database again

    // Get the branch from session
    $branch = $_SESSION['branch'];

    // Updated query to check session branch and token validity
    $query = "
    SELECT rd.* 
    FROM rescue_details rd
    JOIN rescue_selected_stations rss ON rd.id = rss.rescue_details_id
    WHERE rd.incident_id = ? 
    AND rd.auth_token = ? 
    AND rd.status = 'ongoing'
    AND rss.fire_station_name = ? 
    GROUP BY rd.id
    ORDER BY rd.submitted_at ASC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $incident_id, $token, $branch); // Bind incident_id, auth_token, and branch
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // If a valid match is found
        echo json_encode([
            "status" => "success",
            "incident_id" => $incident_id,  // already available from the token
            "latitude" => $decodedData['latitude'],  // from the token
            "longitude" => $decodedData['longitude'],  // from the token
        ]);
    } elseif ($_SESSION['position'] === "Command Officer Head" || $_SESSION['position'] === "Command Officer Staff") {
        // Fetch the selected branches when the session position is Command Officer Head or Staff
        $selectedBranchesQuery = "
        SELECT fire_station_name 
        FROM rescue_selected_stations 
        WHERE rescue_details_id IN (
            SELECT id 
            FROM rescue_details 
            WHERE incident_id = ?
        )";

        $selectedBranchesStmt = $conn->prepare($selectedBranchesQuery);
        $selectedBranchesStmt->bind_param("s", $incident_id); // Bind incident_id
        $selectedBranchesStmt->execute();
        $selectedBranchesResult = $selectedBranchesStmt->get_result();

        $selectedBranches = [];
        while ($branchRow = $selectedBranchesResult->fetch_assoc()) {
            $selectedBranches[] = $branchRow['fire_station_name']; // Add each fire station name to the array
        }

        // Return the selected branches along with the other data
        echo json_encode([
            "status" => "success",
            "incident_id" => $incident_id,  // This is from the decoded token
            "latitude" => $decodedData['latitude'],  // From the token
            "longitude" => $decodedData['longitude'],  // From the token
            "selectedBranches" => $selectedBranches // Return the selected branches

        ]);

        // Close the statement for selected branches
        $selectedBranchesStmt->close();
    } else {
        // If no matching row and position is not authorized
        echo json_encode(["status" => "error", "message" => "Branch not authorized for this incident"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "No token provided"]);
}

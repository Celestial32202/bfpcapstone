<?php
require_once('config.php');

// Fetch all locations with their type names
$sql = "SELECT lm.latitude, lm.longitude, lm.location_name, lt.type_name 
        FROM locations_markers lm
        JOIN location_type_markers lt ON lm.type_id = lt.id";
$result = $conn->query($sql);

$locations = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $locations[] = [
            "latitude" => (float) $row["latitude"],
            "longitude" => (float) $row["longitude"],
            "location" => $row["location_name"],
            "type" => $row["type_name"]
        ];
    }
}

$conn->close();

// Return data as JSON
header('Content-Type: application/json');
echo json_encode(["locations" => $locations]);
<?php
require_once('../../config.php');
require_once '../server/jwt_handler.php';
session_start();
$session_admin = $_SESSION['admin_user'];
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Enable error reporting for debugging

header("Content-Type: application/json"); // ✅ Set response type to JSON
$response = ["success" => false]; // Default response

if ($conn->connect_error) {
    $response["error"] = "Connection failed: " . $conn->connect_error;
    echo json_encode($response);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $connection_id = $_POST["connection_id"] ?? "";
        $report_status = $_POST["report_status"] ?? "";
        $incident_id =  $_POST["incident_id"] ?? "";

        if (!empty($incident_id) && !empty($report_status) && !empty($connection_id)) {
            $updateQuery = "UPDATE incident_report SET report_status = ?, verified_by = ? , verified_at = NOW() WHERE incident_id = ? AND connection_id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("ssss", $report_status, $session_admin, $incident_id, $connection_id);

            if ($stmt->execute()) {
                $token = JWTHandler::encode($incident_id);
                $response = [
                    "success" => true,
                    "connection_id" => $connection_id,
                    "report_status" => $report_status,
                    "incident_id" => $token,
                    "verified_by" => $session_admin,
                    "message" => "Report status updated successfully"
                ];
            } else {
                $response["error"] = "❌ Database Update Failed: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $response["error"] = "❌ Missing parameters: incident_id or report_status";
        }
    } catch (mysqli_sql_exception $e) {
        $response["error"] = "❌ Database Error: " . $e->getMessage();
    }

    $conn->close();
    echo json_encode($response); // ✅ Return JSON response
}
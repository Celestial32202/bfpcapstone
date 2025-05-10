<?php
require_once('../config.php');

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
        $date = date("Ymd");

        $reporter_name = filter_var($_POST["name"] ?? "", FILTER_SANITIZE_STRING);
        $contact_number = filter_var($_POST["contact_number"] ?? "", FILTER_SANITIZE_STRING);
        $incident_location = filter_var($_POST["location"] ?? "", FILTER_SANITIZE_STRING);
        $info_message = filter_var($_POST["message"] ?? "", FILTER_SANITIZE_STRING);
        $connection_id = filter_var($_POST["connection_id"] ?? "", FILTER_SANITIZE_STRING);
        $gps_location = filter_var($_POST["gps_location"] ?? "", FILTER_SANITIZE_STRING);
        $connection_status = "connected";
        $report_status = "pending"; // Default status
        $resident_image_url = "https://rb.gy/ahvfma";

        // Get the last `incident_id` (latest report)
        $query = "SELECT incident_id FROM incident_report ORDER BY id DESC LIMIT 1";
        $result = $conn->query($query);
        $last_incident_id = ($result->num_rows > 0) ? $result->fetch_assoc()['incident_id'] : null;

        // Extract last number from `incident_id`
        if ($last_incident_id && preg_match('/-(\d{6})$/', $last_incident_id, $matches)) {
            $last_number = (int)$matches[1];
        } else {
            $last_number = 0;
        }
        // Generate new `incident_id` (e.g., FIR-20250302-000124)
        $new_number = str_pad($last_number + 1, 6, "0", STR_PAD_LEFT);
        $incident_id = "FIR-$date-$new_number";

        // ✅ Check if `connection_id` exists and get latest report_status
        $checkQuery = "SELECT report_status FROM incident_report WHERE connection_id = ? ORDER BY id DESC LIMIT 1";
        $check_stmt = $conn->prepare($checkQuery);
        $check_stmt->bind_param("s", $connection_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $check_stmt->close();

        $needsNewRow = false; // Flag to determine if a new row should be inserted
        $response = ["success" => false]; // Default response

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $last_status = $row['report_status'];

            if ($last_status === "pending") {
                // ✅ If last report is "Pending", update it
                $updateQuery = "UPDATE incident_report 
                                SET incident_id = ?, 
                                    reporter_name = ?, 
                                    contact_no = ?, 
                                    incident_location = ?, 
                                    info_message = ?,
                                    gps_location = ?, 
                                    report_status = ?, 
                                    resident_image_url = ?, 
                                    connection_status = ?, 
                                    submitted_at = NOW()
                                WHERE connection_id = ?";
                $update_stmt = $conn->prepare($updateQuery);
                $update_stmt->bind_param(
                    "ssssssssss",
                    $incident_id,
                    $reporter_name,
                    $contact_number,
                    $incident_location,
                    $info_message,
                    $gps_location,
                    $report_status,
                    $resident_image_url,
                    $connection_status,
                    $connection_id
                );

                if ($update_stmt->execute()) {
                    // ✅ Fetch updated `submitted_at` time
                    $timeQuery = "SELECT submitted_at FROM incident_report WHERE incident_id = ?";
                    $stmtTime = $conn->prepare($timeQuery);
                    $stmtTime->bind_param("s", $incident_id);
                    $stmtTime->execute();
                    $stmtTime->bind_result($submitted_at);
                    $stmtTime->fetch();
                    $stmtTime->close();

                    // ✅ Format response time
                    $dateTime = new DateTime($submitted_at);
                    $time = $dateTime->format('H:i:s');
                    $twelveHourTime = date("g:i a", strtotime($time));

                    $response = [
                        "success" => true,
                        "incident_id" => $incident_id,
                        "report_status" => $report_status,
                        "submitted_at" => $twelveHourTime,
                        "connection_id" => $connection_id,
                        "message" => "Report updated successfully."
                    ];
                    error_log("✅ Database Updated: User " . $connection_id . " updated report");
                } else {
                    $response["error"] = "❌ Database Update Failed: " . $update_stmt->error;
                }

                $update_stmt->close();
            } else {
                // ✅ If last report is "Approved" or "Declined", a new row should be added
                $needsNewRow = true;
            }
        } else {
            // ✅ No existing connection_id, insert a new row
            $needsNewRow = true;
        }

        if ($needsNewRow) {
            // ✅ Insert a new row for "Approved"/"Declined" or a new submission
            $insertQuery = "INSERT INTO incident_report (incident_id, 
                                                        connection_id, 
                                                        reporter_name, 
                                                        contact_no, 
                                                        incident_location, 
                                                        resident_image_url, 
                                                        info_message, 
                                                        gps_location, 
                                                        report_status, 
                                                        connection_status, 
                                                        submitted_at) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param(
                "ssssssssss",
                $incident_id,
                $connection_id,
                $reporter_name,
                $contact_number,
                $incident_location,
                $resident_image_url,
                $info_message,
                $gps_location,
                $report_status,
                $connection_status
            );

            if ($stmt->execute()) {
                // ✅ Fetch inserted `submitted_at` time
                $timeQuery = "SELECT submitted_at FROM incident_report WHERE incident_id = ?";
                $stmtTime = $conn->prepare($timeQuery);
                $stmtTime->bind_param("s", $incident_id);
                $stmtTime->execute();
                $stmtTime->bind_result($submitted_at);
                $stmtTime->fetch();
                $stmtTime->close();

                // ✅ Format response time
                $dateTime = new DateTime($submitted_at);
                $time = $dateTime->format('H:i:s');
                $twelveHourTime = date("g:i a", strtotime($time));

                $response = [
                    "success" => true,
                    "incident_id" => $incident_id,
                    "report_status" => $report_status,
                    "submitted_at" => $twelveHourTime,
                    "connection_id" => $connection_id,
                    "message" => "New report created successfully."
                ];
                error_log("✅ Database Inserted: New report for User " . $connection_id);
            } else {
                $response["error"] = "❌ Database Insert Failed: " . $stmt->error;
            }

            $stmt->close();
        }
    } catch (mysqli_sql_exception $e) {
        $response["error"] = "Database Error: " . $e->getMessage();
    }

    // ✅ Close database connection and return JSON response
    $conn->close();
    echo json_encode($response);
}
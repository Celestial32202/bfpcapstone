<?php 
    require_once('../config.php'); // <!-- DB Config -->
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    include('includes/header.php'); //<!-- Header -->
    include('includes/siderbar.php'); //<!-- Sidebar -->
    include('includes/navbar.php'); //<!-- Topbar -->
?>

<?php
    if (!isset($_SESSION['permissions']['recieve_rescue_reports']) && $_SESSION['permissions']['recieve_rescue_reports'] != 1) {
        header("Location: dashboard.php");
        exit();
    }
    // Get the token from the URL and session branch
    $token = $_GET['token'] ?? null;
    $branch = $_SESSION['branch'] ?? null;

    // If token or branch is missing, redirect
    if (!$token || !$branch) {
        header("Location: dashboard.php");
        exit();
    }
    // Query to check if the token exists for the given branch
    $query = "
        SELECT rd.*
        FROM rescue_details rd
        JOIN rescue_selected_stations rss ON rd.id = rss.rescue_details_id
        WHERE rd.status = 'ongoing' 
        AND rss.fire_station_name = ? 
        AND rd.auth_token = ?
        ORDER BY rd.submitted_at ASC
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $branch, $token);
    $stmt->execute();
    $query_run = $stmt->get_result();

    // If no matching rescue is found, redirect
    if ($query_run->num_rows === 0) {
        header("Location: dashboard.php");
        exit();
    }
    $stmt->close(); // Close before preparing a new statement

    $decoded = JWTHandler::incident_decode($token);
    if (!$decoded || !isset($decoded['incident_id'])) {
        echo '<pre>';  // Optional for formatting
        var_dump($decoded);  // If it's an object, this will show its properties and values
        echo '</pre>';
        die("❌ Invalid or expired token.");
    }

    $incident_id = htmlspecialchars($decoded['incident_id'], ENT_QUOTES, 'UTF-8'); // ✅ Prevent XSS

    // Secure Query to Fetch Incident Details
    $stmt = $conn->prepare("SELECT incident_location, status, info_message FROM rescue_details WHERE incident_id = ?");
    $stmt->bind_param("s", $incident_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {

        die("❌ Incident not found. " . $incident_id . "");
    }
    $row = $result->fetch_assoc();
    $incident_location = htmlspecialchars($row['incident_location'], ENT_QUOTES, 'UTF-8');
    $info_message = htmlspecialchars($row['info_message'], ENT_QUOTES, 'UTF-8');
    $stmt->close(); // Close before preparing a new statement

?>

<!-- Begin container-fluid -->
<div class="container-fluid">

    <div class="d-sm-flex text-center" style="margin-bottom: 10px;">
        <h4 class="h3 mb-0 text-gray-800">Rescue GPS Tracker</h4>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-body" style="">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div> <br>

    <div class="row" style="margin-bottom: 20px;">
        <div class="col-lg-6 mx-auto text-center">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h4 class="m-0 font-weight-bold text-primary">Destination Details</h4>
                </div>
                <div class="card-body">
                    <p>
                        <code>Incident Location: </code><?php echo $incident_location; ?>
                    </p>
                    <p>
                        <code>Information Message: </code><?php echo $info_message; ?>
                    </p>
                    <button type="button" id="returningBtn" class="mt-2 btn btn-primary" disabled>Returning</button>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?php include('includes/footer.php'); ?>
<script src="js/tracker-map.js"> </script>
<?php include('includes/scripts.php'); ?>
<?php include('includes/header.php'); ?>
<!-- Sidebar -->
<?php include('includes/siderbar.php'); ?>
<!-- End of Sidebar -->
<!-- Topbar -->
<?php include('includes/navbar.php'); ?>
<!-- End of Topbar -->
<?php

if (!isset($_SESSION['permissions']['manage_reports']) && $_SESSION['permissions']['manage_reports'] != 1) {
    header("Location: dashboard.php");
    exit();
}
$token = isset($_GET['token']) ? trim($_GET['token']) : ''; // ✅ Validate token input
if (!$token) {
    die("❌ No token provided.");
}
// ✅ Decode the token safely
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

?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h4 class="h3 mb-0 text-gray-800">Rescue GPS Tracker</h4>
    </div>
    <div class="row">
        <div class="col-lg-9 h-100">
            <div class="card shadow">
                <div class="card-body">
                    <div id="map"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 h-100">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class=" font-weight-bold text-primary">
                        Rescue Monitoring Details
                    </h6>
                </div>
                <div class="card-body" style="margin-top: -25px;">
                    <p>
                        <code>Incident Location:</code><?php echo $incident_location; ?>
                    </p>
                    <p>
                        <code>Information Message:</code><?php echo $info_message; ?>
                    </p>
                    <p><code>Fire Stations Dispatched: </code></p>
                    <p id="dispatched-stations">
                        <!-- This will be populated by JavaScript -->
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<?php include('includes/footer.php'); ?>
<script src="js/rescue-monitoring-map.js"> </script>
<?php include('includes/scripts.php'); ?>
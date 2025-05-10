<?php
require_once('../config.php');
require_once('configs/jwt_handler.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


$token = isset($_GET['token']) ? urldecode(trim($_GET['token'])) : '';

if (!$token) {
    die("❌ No token provided.");
}
// ✅ Decode the token safely
$decoded = JWTHandler::decode($token);
if (!$decoded || !isset($decoded->incident_id)) {
    die("❌ Invalid or expired token.");
}
$incident_id = htmlspecialchars($decoded->incident_id, ENT_QUOTES, 'UTF-8'); // ✅ Prevent XSS

// Secure Query to Fetch Incident Details
$stmt = $conn->prepare("SELECT incident_location, info_message FROM incident_report WHERE incident_id = ?");
$stmt->bind_param("s", $incident_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {

    die("❌ Incident not found. " . $incident_id . "");
}
$row = $result->fetch_assoc();
$incident_location = htmlspecialchars($row['incident_location'], ENT_QUOTES, 'UTF-8');
$info_message = htmlspecialchars($row['info_message'], ENT_QUOTES, 'UTF-8');

// Close database connection
$stmt->close();
?>
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
} ?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h4 class="h3 mb-0 text-gray-800">Choose Pin of Reported Fire Location</h4>
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
                <div class="card-header ">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Destination Details
                    </h6>
                </div>
                <div class="card-body m-0">
                    <p>
                        <code>incident_id:</code><?php echo $incident_id ?>
                    </p>
                    <p>
                        <code>Incident Location:</code><?php echo $incident_location ?>
                    </p>
                    <p>
                        <code>Information Message:</code><?php echo $info_message ?>
                    </p>
                    <p>
                        <code>Longitude:</code>Here
                    </p>
                    <p>
                        <code>Latitude:</code>Here
                    </p>
                    <p id="fireStationsList"><code>Nearest Fire Stations:</code> None</p>
                    <p>
                        <code>Choose How Many Stations to send:</code>
                    <div class="select-container mb-3" style="width: 200px;">
                        <select class="form-select" id="quantity" name="quantity" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                    </p>
                    <button type="button" id="sendDestination" class="mt-2 btn btn-primary"
                        data-dismiss="">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Loading Modal -->
<div class="modal fade" id="redirectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4">
            <div class="modal-body mt-3">
                <div class="spinner "></div>
                <h4 class="mt-4 ">Redirecting in <span id="countdown">3</span>...</h4>
            </div>
        </div>
    </div>
</div>

<!-- /.container-fluid -->

<?php include('includes/footer.php'); ?>
<script src="js/map-incident-loc.js"> </script>
<?php include('includes/scripts.php'); ?>
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
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Pending Reports</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Today Reports</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php
                $query = "SELECT * FROM incident_report Where report_status = 'pending' OR report_status = 'processing' ORDER BY submitted_at DESC";
                $query_run = mysqli_query($conn, $query);
                ?>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Incident ID</th>
                            <th>Name</th>
                            <th>Contact#</th>
                            <th>Incident<br>Location</th>
                            <th>Message</th>
                            <th>Time Submitted</th>
                            <th>Status </th>
                            <th>Connection<br>Status</th>
                            <th>Options </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Incident ID</th>
                            <th>Name</th>
                            <th>Contact#</th>
                            <th>Incident<br>Location</th>
                            <th>Message</th>
                            <th>Time<br>Submitted</th>
                            <th>Status </th>
                            <th>Connection<br>Status</th>
                            <th>Options </th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($query_run) > 0) {
                            while ($row = mysqli_fetch_assoc($query_run)) {
                        ?>
                        <tr data-user-id="<?php echo $row['connection_id']; ?> "
                            data-row-id="<?php echo $row['incident_id']; ?> ">
                            <td><?php echo $row['incident_id']; ?></td>
                            <td><?php echo $row['reporter_name']; ?></td>
                            <td><?php echo $row['contact_no']; ?></td>
                            <td><?php echo $row['incident_location']; ?></td>
                            <td><?php echo $row['info_message']; ?></td>
                            <td><?php $submittedTime = $row['submitted_at'];
                                        $dateTime = new DateTime($submittedTime);
                                        $time = $dateTime->format('H:i:s');
                                        $twelveHourTime = date("g:i a", strtotime($time));
                                        echo $twelveHourTime;
                                        ?></td>
                            <td>
                                <span class="badge 
                                <?php
                                if ($row['report_status'] === 'processing') {
                                    echo 'badge-info';
                                } elseif ($row['report_status'] === 'pending') {
                                    echo 'badge-warning';
                                } else {
                                    echo 'badge-secondary'; // Default badge color for other statuses
                                }
                                ?>">
                                    <h6>
                                        <?php
                                                if ($row['report_status'] === 'processing') {
                                                    echo "processing by " . $row['verified_by'];
                                                } else {
                                                    echo $row['report_status']; // ✅ Display actual status if not "processing"
                                                }
                                                ?>
                                    </h6>
                                </span>
                            </td>


                            <td><?php
                                        if ($row['connection_status'] === "Connected") {
                                            echo '<span class="status-cell badge badge-success"><h6>Connected</h6></span>';
                                        } else {
                                            echo '<span class="status-cell badge badge-danger"><h6>Disconnected</h6></span>';
                                        }
                                        ?></td>
                            <td>
                                <?php
                                        $gpsLocation = $row['gps_location'];
                                        $gpsAttributes = '';

                                        if (strpos($gpsLocation, ',') !== false) {
                                            // ✅ GPS Success (lat, lon)
                                            list($lat, $lon) = explode(',', $gpsLocation);
                                            $gpsAttributes = "data-lat=\"$lat\" data-lon=\"$lon\"";
                                        } elseif (!empty($gpsLocation)) {
                                            // ❌ GPS Error
                                            $gpsAttributes = "data-errorGps=\"$gpsLocation\"";
                                        }
                                        ?>
                                <button class="view-row btn btn-primary" data-toggle="modal"
                                    data-target="#incidentModal" data-userid="<?php echo $row['connection_id']; ?>"
                                    data-id="<?php echo $row['incident_id']; ?>"
                                    data-name="<?php echo $row['reporter_name']; ?>"
                                    data-contact="<?php echo $row['contact_no']; ?>"
                                    data-location="<?php echo $row['incident_location']; ?>"
                                    data-message="<?php echo $row['info_message']; ?>"
                                    data-time="<?php echo date('g:i a', strtotime($row['submitted_at'])); ?>"
                                    data-status="<?php echo $row['report_status']; ?>" <?php echo $gpsAttributes; ?>
                                    data-verified-by="<?php echo $row['verified_by']; ?>"
                                    data-residentImage="<?php echo $row['resident_image_url']; ?>"
                                > View
                                </button>
                            </td>
                        </tr>
                        <?php
                            }
                        } else {
                            echo "No Record Found";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
<!-- Bootstrap Modal -->
<div class="modal fade container-fluid" id="incidentModal" tabindex="-1" aria-labelledby="modalTitle">
    <div class="modal-dialog " style="max-width: 1000px; min-height: calc(100% - 15rem); display: flex;
  align-items: center;">
        <div class="modal-content">
            <div class="modal-header d-sm-flex align-items-center justify-content-between mb-3">
                <h5 class="modal-title m-0 font-weight-bold text-primary" id="modalTitle">Reporter Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class=" col-xl-5" style="word-wrap: break-word;">
                        <p><strong>Incident ID:</strong> <span id="modalIncidentID"></span></p>
                        <p><strong>Name:</strong> <span id="modalName"></span></p>
                        <p><strong>Contact:</strong> <span id="modalContact"></span></p>
                        <p><strong>Location:</strong> <span id="modalLocation"></span></p>
                        <p><strong>Message:</strong> <span id="modalMessage"></span></p>
                        <p><strong>Time Submitted:</strong> <span id="modalTime"></span></p>
                        <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                        <p id="modalGpsLocation"><strong>GPS Status:</strong> Loading...</p>  <br> <br>
                        <p><strong>Resident Image:</strong></p> <br>
                        <img id="residentImageURL" style="height: 250px; width: 250px;"/>
                    </div>
                    <div class="col-xl-7 ">
                        <h5 class="m-0 font-weight-bold text-primary mb-2">
                            User Location Coordinates
                        </h5>
                        <div class="card shadow">

                            <div id="row-map"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary call-btn" data-id="" data-userid="">Call</button> -->
                <button type="button" class="btn btn-success approve-btn" data-userid="" data-id=""
                    data-dismiss="modal">Approve</button>
                <button type="button" class="btn btn-danger decline-btn" data-userid="" data-id=""
                    data-dismiss="modal">Decline</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>
<!-- Bootstrap core JavaScript-->
<script>
let loggedInAdmin = "<?php echo $_SESSION['admin_user']; ?>";
</script>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="js/demo/datatables-demo.js"></script>
<script src="js/vc-request.js"></script>
<script src="js/modal.js"></script>

</body>

</html>
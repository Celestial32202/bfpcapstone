<?php include('includes/header.php'); ?>
<!-- Sidebar -->
<?php include('includes/siderbar.php'); ?>
<!-- End of Sidebar -->
<!-- Topbar -->
<?php include('includes/navbar.php'); ?>
<!-- End of Topbar -->
<?php
if (!isset($_SESSION['permissions']['recieve_rescue_reports']) && $_SESSION['permissions']['recieve_rescue_reports'] != 1) {
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
                $branch = $_SESSION['branch']; // Get branch from session
                $query = "
                    SELECT rd.*
                    FROM rescue_details rd
                    JOIN rescue_selected_stations rss ON rd.id = rss.rescue_details_id
                    WHERE rd.status = 'ongoing' 
                    AND rss.fire_station_name = ?
                    ORDER BY rd.submitted_at ASC";

                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $branch);
                $stmt->execute();
                $query_run = $stmt->get_result();
                ?>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Incident ID</th>
                            <th>Incident Location</th>
                            <th>Information Message</th>
                            <th>Status</th>
                            <th>Time Assigned</th>
                            <th>Options </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Incident ID</th>
                            <th>Incident Location</th>
                            <th>Information Message</th>
                            <th>Status</th>
                            <th>Time Assigned</th>
                            <th>Options </th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($query_run) > 0) {
                            while ($row = mysqli_fetch_assoc($query_run)) {
                        ?>
                        <tr data-id="<?php echo $row['id']; ?>">
                            <td><?php echo $row['incident_id'];
                                        $fetchedToken = $row['auth_token'];
                                        $decodedData = JWTHandler::incident_decode($fetchedToken);
                                        echo $decodedData['incident_id'];
                                        ?></td>
                            <td><?php echo $row['incident_location']; ?></td>
                            <td><?php echo $row['info_message']; ?></td>
                            <td><span class="badge badge-warning">
                                    <h6><?php echo $row['status']; ?></h6>
                                </span></td>

                            <td><?php $submittedTime = $row['submitted_at'];
                                        $dateTime = new DateTime($submittedTime);
                                        $time = $dateTime->format('H:i:s');
                                        $twelveHourTime = date("g:i a", strtotime($time));
                                        echo $twelveHourTime;
                                        ?></td>
                            <td>
                                <?php
                                        $destinationLat = $row['latitude'];
                                        $destinationLong = $row['longitude'];
                                        $gpsAttributes = (!empty($destinationLat) && !empty($destinationLong))
                                            ? "data-lat=\"$destinationLat\" data-lon=\"$destinationLong\""
                                            : "";
                                        ?>
                                <button class="view-row btn btn-primary" data-toggle="modal"
                                    data-target="#incidentModal" data-id="<?php echo $row['id']; ?>"
                                    data-incident_token="<?php echo $row['auth_token']; ?>"
                                    data-incident_id="<?php echo $row['incident_id']; ?>"
                                    data-incident_location="<?php echo $row['incident_location']; ?>"
                                    data-info_message="<?php echo $row['info_message']; ?>"
                                    data-status="<?php echo $row['status']; ?>"
                                    data-time="<?php echo date('g:i a', strtotime($row['submitted_at'])); ?>"
                                    <?php echo $gpsAttributes; ?>>
                                    View
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
                        <p><strong>Incident Location:</strong> <span id="modalLocation"></span></p>
                        <p><strong>Information Message:</strong> <span id="modalMessage"></span></p>
                        <p><strong>Time Submitted:</strong> <span id="modalTime"></span></p>
                        <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                        <p id="modalGpsLocation"><strong>GPS Status:</strong> Loading...</p>
                    </div>
                    <div class="col-xl-7 ">
                        <h5 class="m-0 font-weight-bold text-primary mb-2">
                            Fire Incident Location
                        </h5>
                        <div class="card shadow">
                            <div id="row-map"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary accept-btn" data-incident_token="">Accept</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>
<!-- Bootstrap core JavaScript-->
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
<script src="js/ong-rescues.js"></script>

</body>

</html>
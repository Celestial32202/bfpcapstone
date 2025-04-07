<?php
require_once('../config.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

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
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Declined Reports</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Declined Reports</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php
                $query = "SELECT * FROM incident_report Where report_status = 'declined' ORDER BY submitted_at DESC";
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
                            <th>Date Submitted</th>
                            <th>Time<br>Submitted</th>
                            <th>Declined By</th>
                            <th>Time Declined</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Incident ID</th>
                            <th>Name</th>
                            <th>Contact#</th>
                            <th>Incident<br>Location</th>
                            <th>Message</th>
                            <th>Date Submitted</th>
                            <th>Time<br>Submitted</th>
                            <th>Declined By</th>
                            <th>Time Declined</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($query_run) > 0) {
                            while ($row = mysqli_fetch_assoc($query_run)) {
                        ?>
                        <tr>
                            <td><?php echo $row['incident_id']; ?></td>
                            <td><?php echo $row['reporter_name']; ?></td>
                            <td><?php echo $row['contact_no']; ?></td>
                            <td><?php echo $row['incident_location']; ?></td>
                            <td><?php echo $row['info_message']; ?></td>
                            <td><?php $submittedDate = $row['submitted_at'];
                                        $dateTime = new DateTime($submittedDate);
                                        $date = $dateTime->format('F d, Y');
                                        $formattedDate = $dateTime->format('F d, Y');
                                        echo $formattedDate;;

                                        ?></td>
                            <td><?php $submittedTime = $row['submitted_at'];
                                        $dateTime = new DateTime($submittedTime);
                                        $time = $dateTime->format('H:i:s');
                                        $twelveHourTime = date("g:i a", strtotime($time));
                                        echo $twelveHourTime;
                                        ?></td>
                            <td><?php echo $row['verified_by']; ?></td>
                            <td><?php $submittedTime = $row['verified_at'];
                                        $dateTime = new DateTime($submittedTime);
                                        $time = $dateTime->format('H:i:s');
                                        $twelveHourTime = date("g:i a", strtotime($time));
                                        echo $twelveHourTime;
                                        ?></td>
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
    <div class="modal-dialog " style="max-width: 600px; min-height: calc(100% - 15rem); display: flex;
  align-items: center;">
        <div class="modal-content">
            <div class="modal-header d-sm-flex align-items-center justify-content-between mb-3">
                <h5 class="modal-title m-0 font-weight-bold text-primary" id="modalTitle">Reporter Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="container-fluid">
                <div class="row ">
                    <div class=" col-xl-12" style="word-wrap: break-word;">
                        <p><strong>Incident ID:</strong> <span id="modalIncidentID"></span></p>
                        <p><strong>Name:</strong> <span id="modalName"></span></p>
                        <p><strong>Contact:</strong> <span id="modalContact"></span></p>
                        <p><strong>Location:</strong> <span id="modalLocation"></span></p>
                        <p><strong>Message:</strong> <span id="modalMessage"></span></p>
                        <p><strong>Time Submitted:</strong> <span id="modalTime"></span></p>
                        <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary call-btn" data-dismiss="">Call</button>
                <button type="button" class="btn btn-success" data-dismiss="">Accept</button>
                <button type="button" class="btn btn-danger" data-dismiss="">Decline</button>
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
<script src="js/approved-rpts.js"></script>
</body>

</html>
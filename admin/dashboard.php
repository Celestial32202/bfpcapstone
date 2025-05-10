<?php 
    include('includes/header.php');
    include('includes/siderbar.php');
    include('includes/navbar.php');

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $approved = "Approved";
    $pending = "pending";
    $declined = "Declined";

    function getTodayReportsCount($conn)
    {
        $sql = "SELECT COUNT(*) AS total 
                FROM incident_report 
                WHERE DATE(submitted_at) = CURDATE()";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }
    function getCurrentMonthsReportsCount($conn)
    {
        $sql = "SELECT COUNT(*) AS total 
                FROM incident_report 
                WHERE YEAR(submitted_at) = YEAR(CURDATE()) 
                AND MONTH(submitted_at) = MONTH(CURDATE())";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }
    function getIncidentReportsByStatus($conn, $status)
    {
        $sql = "SELECT COUNT(*) AS total FROM incident_report WHERE report_status = '$status'";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
            return "";
        }

        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }
    function getAcceptedFireRescues($conn)
    {
        $sql = "SELECT COUNT(*) AS accepted FROM accepted_fire_rescues";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
            return "";
        }

        $row = mysqli_fetch_assoc($result);
        return $row['accepted'];
    }
    function getOnGoingFireRescues($conn)
    {
        $sql = "SELECT COUNT(*) AS accepted FROM rescue_details";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
            return "";
        }

        $row = mysqli_fetch_assoc($result);
        return $row['accepted'];
    }
    function getLocationMarkers($conn)
    {
        $sql = "SELECT COUNT(*) AS location_markers FROM locations_markers";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
            return "";
        }

        $row = mysqli_fetch_assoc($result);
        return $row['location_markers'];
    }
    /* function getPendingReportsCount($conn)
    {
        $sql = "SELECT COUNT(*) AS total 
                FROM incident_report 
                WHERE report_status = 'pending'";

        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    } */

    $monthly_reports = getCurrentMonthsReportsCount($conn);
    $today_reports = getTodayReportsCount($conn);
    $pending_reports = getIncidentReportsByStatus($conn, $pending); //getPendingReportsCount($conn);
    $total_approved_incident_reports = getIncidentReportsByStatus($conn, $approved);
    $total_declined_incident_reports = getIncidentReportsByStatus($conn, $declined);
    $total_pending_incident_reports = getIncidentReportsByStatus($conn, $pending);
    $total_accepted_fire_rescues_reports = getAcceptedFireRescues($conn);
    $total_on_going_fire_rescues_reports = getOnGoingFireRescues($conn);
    $total_location_markers = getLocationMarkers($conn);
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"></h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div> -->

    <!-- Content Row -->
    <div class="row">

        <!-- Fire Incident Reports (Monthly) Card Display -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Fire Incident Reports (Monthly)</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $monthly_reports; ?></div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $monthly_reports; ?>%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="200"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Fire Incident Reports (Today)</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $today_reports; ?></div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $today_reports; ?>%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="200"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Location Markers</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $total_location_markers; ?></div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $total_location_markers; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-map fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning s  hadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Reports in queue</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $pending_reports; ?></div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $pending_reports; ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x "></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->

    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Incident Reports Overview</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Fire Rescues Overview</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Accepted Fire Rescues
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> On Going Fire Rescues
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content Row -->

    <!-- Handle values from the Server -->
    <input id="incident-report-total-approved" type="hidden" value="<?php echo $total_approved_incident_reports; ?>">
    <input id="incident-report-total-declined" type="hidden" value="<?php echo $total_declined_incident_reports; ?>">
    <input id="incident-report-total-pending" type="hidden" value="<?php echo $total_pending_incident_reports; ?>">
    <input id="accepted-fire-rescues-total" type="hidden" value="<?php echo $total_accepted_fire_rescues_reports; ?>">
    <input id="on-going-fire-rescues-total" type="hidden" value="<?php echo $total_on_going_fire_rescues_reports; ?>">


</div>
<!-- /.container-fluid -->

<?php include('includes/footer.php'); ?>
<?php include('includes/scripts.php'); ?>
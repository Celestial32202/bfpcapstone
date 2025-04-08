<?php
include('includes/header.php');
include('includes/navbar.php');


$totalUsers = getTotalUsers();
$totalSurveys = getTotalSurveys();
$totalNewsUpdates = getTotalNewsUpdates();
?>

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-user fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Total Registered Users</p>
                    <h6 class="mb-0"><?php echo $totalUsers; ?></h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-file-alt fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Submitted Survey</p>
                    <h6 class="mb-0"><?php echo $totalSurveys; ?></h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-sync fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Total Posted Updates</p>
                    <h6 class="mb-0"><?php echo $totalNewsUpdates; ?></h6>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
include('includes/script.php');

?>

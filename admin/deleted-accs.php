<?php include('includes/header.php'); ?>
<!-- Sidebar -->
<?php include('includes/siderbar.php'); ?>
<!-- End of Sidebar -->
<!-- Topbar -->
<?php include('includes/navbar.php'); ?>
<!-- End of Topbar -->
<?php
if (!isset($_SESSION['permissions']['manage_accounts']) && $_SESSION['permissions']['manage_accounts'] != 1) {
    header("Location: dashboard.php");
    exit();
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Deleted Accounts</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lists of Accounts</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php
                $query = "SELECT * FROM admin_creds WHERE is_deleted = 1";
                $query_run = mysqli_query($conn, $query);
                ?>
                <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Position</th>
                            <th>Branch</th>
                            <th>Contact#</th>
                            <th>Email</th>
                            <th>Verified</th>
                            <th>Login Attempts</th>
                            <th>Last Failed Login</th>
                            <th>Locked</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Position</th>
                            <th>Branch</th>
                            <th>Contact#</th>
                            <th>Email</th>
                            <th>Verified</th>
                            <th>Login Attempts</th>
                            <th>Last Failed Login</th>
                            <th>Locked</th>
                            <th>Options</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($query_run) > 0) {
                            while ($row = mysqli_fetch_assoc($query_run)) {
                        ?>
                        <tr data-user-id="<?php echo $row['admin_id']; ?> ">
                            <td><?php echo $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']; ?>
                            </td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['admin_position']; ?></td>
                            <td><?php echo $row['branch']; ?></td>
                            <td><?php echo $row['contact_number']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td>
                                <?php

                                        if ($row['verified'] == 1) {
                                            echo '<i class="fas fa-check-circle text-success fa-lg"></i>';
                                        } else {
                                            echo '<i class="fas fa-times-circle text-warning fa-lg"></i>';
                                        }
                                        ?>
                            </td>
                            <td>
                                <?php
                                        $attempts = $row['failed_attempts'];

                                        if ($attempts == 0) {
                                            echo '<span class="text-success"><i class="fas fa-check-circle "></i> No attempts</span>';
                                        } elseif ($attempts == 1) {
                                            echo '<span class="text-warning"><i class="fas fa-exclamation-circle"></i> 1 attempt</span>';
                                        } elseif ($attempts == 2) {
                                            echo '<span class="text-orange" style="color: orange;"><i class="fas fa-exclamation-triangle"></i> 2 attempts</span>';
                                        } elseif ($attempts >= 3) {
                                            echo '<span class="text-danger"><i class="fas fa-times-circle"></i> ' . $attempts . ' attempts</span>';
                                        }
                                        ?>
                            </td>

                            <td>
                                <?php

                                        $failed_date = $row['last_failed_login'];
                                        $dateTime = new DateTime($failed_date);
                                        echo $dateTime->format('F j, Y g:i a');
                                        ?>
                            </td>
                            <td>
                                <?php
                                        if ($row['is_locked'] == 1) {
                                            echo '<i class="fas fa-shield-alt text-warning  fa-lg"></i>';
                                        } else {
                                            echo '<i class="fas fa-unlock-alt text-info fa-lg"></i>';
                                        }
                                        ?>
                            </td>
                            <td>
                                <!-- Undelete Icon (Recycle/Restore) -->
                                <i class="fas fa-undo text-success fa-lg undelete-user" title="Restore"
                                    style="cursor: pointer;"></i>
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
<!-- Confirmation Modal -->
<div class="modal fade" id="confirmActionModal" tabindex="-1" role="dialog" aria-labelledby="confirmActionLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmActionLabel">Confirm Action</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="confirmActionMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmActionBtn" class="btn btn-primary">Confirm</button>
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
<script src="js/acc-mngmnt.js"></script>

</body>

</html>
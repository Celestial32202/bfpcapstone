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
    <h1 class="h3 mb-2 text-gray-800">Submitted News</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <?php
                $query = "SELECT * FROM news";
                $query_run = mysqli_query($conn, $query);
                ?>
                <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">Image</th>
                            <th class="text-center">Subject</th>
                            <th class="text-center">Other Details</th>
                            <!-- <th>Actions</th> -->
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center">Image</th>
                            <th class="text-center">Subject</th>
                            <th class="text-center">Other Details</th>
                            <!-- <th>Actions</th> -->
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($query_run) > 0) {
                            while ($row = mysqli_fetch_assoc($query_run)) {
                        ?>
                        <tr data-user-id="<?php echo $row['admin_id']; ?> ">
                            <td class="text-center">
                                <center>
                                    <img src="<?php echo $row['news_image']; ?>" style="width: 150px; height: 150px;">
                                </center>
                            </td>
                            <td class="text-center"><?php echo $row['news_subject']; ?></td>
                            <td class="text-center"><?php echo $row['news_other_details']; ?></td>
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
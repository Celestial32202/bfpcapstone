<?php ob_start(); // must be first!

include('includes/header.php');
?>
<!-- Sidebar -->
<?php include('includes/siderbar.php'); ?>
<!-- End of Sidebar -->
<!-- Topbar -->
<?php include('includes/navbar.php'); ?>
<!-- End of Topbar -->

<!-- Begin Page Content -->
<?php
ob_start();
if (!isset($_SESSION['permissions']['manage_accounts']) && $_SESSION['permissions']['manage_accounts'] != 1) {
    header("Location: dashboard.php");
    exit();
}
// Get user_id from the POST request
if (isset($_POST['admin_id'])) {
    $admin_id = $_POST['admin_id'];

    // Fetch user data from database (assuming you have a table named admin_creds)
    $query = "SELECT * FROM admin_creds WHERE admin_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        // Handle case if user not found
        $_SESSION['error_message'] = "User not found.";
        header("Location: acc-management.php");
        exit();
    }
} else {
    // Handle case if no user_id is provided
    $_SESSION['error_message'] = "No user ID provided.";
    header("Location: acc-management.php");
    exit();
}

// Check if form is submitted to update the user data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_form'])) {
    // Validate and sanitize form input here
    $admin_id = $_POST['admin_id'];
    $firstName = $_POST['first_name'];
    $middleName = $_POST['middle_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $mbNumber = $_POST['mb_number'];
    $position = $_POST['position'];
    $branch = $_POST['branch'];

    if ($position !== 'Fire Officer' && $position !== 'Fire Officer Supervisor') {
        $branch = $position;
    }
    // Generate permissions based on position
    $permissions = [];
    switch ($position) {
        case 'Command Officer Staff':
            $permissions = [
                "main_dashboard" => 1,
                "manage_accounts" => 1,
                "manage_reports" => 1,
                "monitor_rescue" => 1
            ];
            break;
        case 'Fire Officer Supervisor':
            $permissions = [
                "main_dashboard" => 1,
                "submit_accounts" => 1,
                "recieve_rescue_reports" => 1
            ];
            break;
        case 'Fire Officer':
            $permissions = [
                "main_dashboard" => 1,
                "recieve_rescue_reports" => 1
            ];
            break;
        default:
            throw new Exception("Invalid position selected");
    }

    // Encode to JSON for storage
    $permissions_json = json_encode($permissions);

    // Update the user in the database
    $updateQuery = "UPDATE admin_creds SET first_name = ?, middle_name = ?, last_name = ?, email = ?, contact_number = ?, admin_position = ?, branch = ?, admin_permissions = ? WHERE admin_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("sssssssss", $firstName, $middleName, $lastName, $email, $mbNumber, $position, $branch, $permissions_json, $admin_id);


    if ($updateStmt->execute()) {
        $_SESSION['success_message'] = "Account updated successfully.";
        header("Location: acc-management.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Failed to update account.";
    }
}
?>
<div class="container-fluid ">
    <!-- <div class="col-sm-12 col-xl-6 justify-content-start"></div> -->
    <h1 class="h3 mb-2 text-gray-800">Editing User <?php echo $user['username']; ?>
    </h1>
    <div class="col-sm-12 col-xl-6 justify-content-start">
        <div class="card shadow ">

            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Change details</h6>
            </div>
            <div class="container">
                <?php
                if (isset($_SESSION['error_message'])) {
                    echo $_SESSION['error_message'];
                    unset($_SESSION['error_message']);
                }
                ?>
                <section class="w3l-mockup-form">
                    <div class="reg-container">
                        <div class="workinghny-form-grid">
                            <div class="main-mockup">
                                <div class="alert-close">
                                    <span class="fa fa-close"></span>
                                </div>
                                <div class="content-wthree">
                                    <form id="register_form" name="register_form" method="post" action="">
                                        <div class="row">
                                            <input type="hidden" name="admin_id" value="<?php echo $admin_id; ?>">
                                            <div class="col-xl-6">
                                                <input type="text" class="name" name="first_name"
                                                    value="<?php echo htmlspecialchars($user['first_name']); ?>"
                                                    placeholder="First Name" required>
                                            </div>
                                            <div class="col-xl-6">
                                                <input type="text" class="name" name="middle_name"
                                                    value="<?php echo htmlspecialchars($user['middle_name']); ?>"
                                                    placeholder="Middle Name" required>
                                            </div>
                                            <div class="col-xl-6">
                                                <input type="text" class="name" name="last_name"
                                                    value="<?php echo htmlspecialchars($user['last_name']); ?>"
                                                    placeholder="Last Name" required>
                                            </div>
                                            <div class="col-xl-6">
                                                <input type="email" class="email" name="email"
                                                    value="<?php echo htmlspecialchars($user['email']); ?>"
                                                    placeholder="Enter Your Email" required>
                                            </div>
                                            <div class="col-xl-6">
                                                <input type="text" class="mb_number" name="mb_number"
                                                    value="<?php echo htmlspecialchars($user['contact_number']); ?>"
                                                    placeholder="Enter Your Mobile Number" pattern="09\d{9}"
                                                    oninput="limitInput(this, 11)"
                                                    onkeypress="return restrictInput(event)" required>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="select-container mb-3">
                                                    <select class="form-select" id="position" name="position" required>
                                                        <option value="position-none"
                                                            <?php echo ($user['admin_position'] == 'position-none') ? 'selected' : ''; ?>>
                                                            Choose Position</option>
                                                        <option value="Fire Officer"
                                                            <?php echo ($user['admin_position'] == 'Fire Officer') ? 'selected' : ''; ?>>
                                                            Fire Officer</option>
                                                        <option value="Fire Officer Supervisor"
                                                            <?php echo ($user['admin_position'] == 'Fire Officer Supervisor') ? 'selected' : ''; ?>>
                                                            Fire Officer Supervisor</option>
                                                        <option value="Command Officer Staff"
                                                            <?php echo ($user['admin_position'] == 'Command Officer Staff') ? 'selected' : ''; ?>>
                                                            Command Officer Staff</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="branch-select-container mb-3">
                                                    <select class="form-select" id="branch" name="branch" required>
                                                        <option value="branch-none"
                                                            <?php echo ($user['branch'] == 'branch-none') ? 'selected' : ''; ?>>
                                                            What Branch</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-xl-8 ">
                                                <button name="submit_form" id="register_btn" class="btn btn-primary"
                                                    type="submit">Submit</button>

                                            </div>
                                            <div class="col-xl-4 ">
                                                <button type="button" class="btn btn-secondary"
                                                    onclick="window.location.href='acc-management.php'">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<!-- Bootstrap Modal -->

<?php include('includes/footer.php'); ?>
<script>
const currentBranch = "<?php echo htmlspecialchars($user['branch']); ?>";
</script>
<script src="js/edit-account.js"></script>
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
</body>

</html>
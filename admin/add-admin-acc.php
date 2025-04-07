<?php include('includes/header.php'); ?>
<!-- Sidebar -->
<?php include('includes/siderbar.php'); ?>
<!-- End of Sidebar -->
<!-- Topbar -->
<?php include('includes/navbar.php'); ?>
<!-- End of Topbar -->

<!-- Begin Page Content -->
<?php
if (!isset($_SESSION['permissions']['manage_accounts']) && $_SESSION['permissions']['manage_accounts'] != 1) {
    header("Location: dashboard.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password']) && !isset($_POST['submit_form'])) {
    $password = $_POST['password'];
    $requirements = validatePasswordRequirements($password);
    echo json_encode(['requirements' => array_values($requirements)]);
    exit;
}
function validatePasswordRequirements($password)
{
    $requirements = [
        'length' => strlen($password) >= 8,
        'number' => preg_match('/\d/', $password),
        'lowercase' => preg_match('/[a-z]/', $password),
        'special' => preg_match('/[!$%^&*()\-_=+{};:,.#~`\[\]\\\|"\',?\/@<>]/', $password),
        'uppercase' => preg_match('/[A-Z]/', $password)
    ];
    return $requirements;
}
?>
<div class="container-fluid ">
    <!-- <div class="col-sm-12 col-xl-6 justify-content-start"></div> -->
    <h1 class="h3 mb-2 text-gray-800">Register New Administrator</h1>
    <div class="col-sm-12 col-xl-6 justify-content-start">
        <div class="card shadow ">

            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Fill up form</h6>
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
                                    <form id="register_form" name="register_form" method="post"
                                        action="accs/register.php">
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <input type="text" class="name" name="first_name"
                                                    placeholder="First Name" required>
                                            </div>
                                            <div class="col-xl-6">
                                                <input type="text" class="name" name="middle_name"
                                                    placeholder="Middle Name" required>
                                            </div>
                                            <div class="col-xl-6">
                                                <input type="text" class="name" name="last_name" placeholder="Last Name"
                                                    required>
                                            </div>
                                            <div class="col-xl-6">
                                                <input type="email" class="email" name="email"
                                                    placeholder="Enter Your Email" required>
                                            </div>
                                            <div class="col-xl-6">
                                                <input type="text" class="mb_number" name="mb_number"
                                                    placeholder="Enter Your Mobile Number" pattern="09\d{9}"
                                                    oninput="limitInput(this, 11)"
                                                    onkeypress="return restrictInput(event)" required>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="select-container mb-3">
                                                    <select class="form-select" id="position" name="position" required>
                                                        <option value="position-none">Choose Position</option>
                                                        <option value="Fire Officer">Fire Officer</option>
                                                        <option value="Fire Officer Supervisor">Fire Officer Supervisor
                                                        </option>
                                                        <option value="Command Officer Staff">Command Officer Staff
                                                        </option>
                                                        <option value="Command Officer Head">Command Officer Head
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="branch-select-container mb-3">
                                                    <select class="form-select" id="branch" name="branch" required>
                                                        <option value="branch-none">What Branch</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-sm-9 col-xl-9">
                                                <input type="text" id="password" class="password" name="password"
                                                    placeholder="Enter Your Password" onclick="" required disabled />
                                            </div>
                                            <div class="col-sm-3 col-xl-3">
                                                <button type="button" class="btn-primary generate"
                                                    onclick="generatePassword()">Generate</button>
                                            </div>
                                        </div>
                                        <button name="submit_form" id="register_btn" class="btn-primary mt-3 mb-4"
                                            type="submit_frm" disabled>Register</button>
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
<script src="js/add-admin.js"></script>
<?php include('includes/reg-script.php') ?>
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
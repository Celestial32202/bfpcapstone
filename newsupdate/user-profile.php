<?php
$currentPage = "";
include ('includes/header.php'); 
include ('includes/navbar.php'); 
require_once('config.php');
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
$user_email = $_SESSION['SESSION_EMAIL'];

?>

<div class="" style="background-color: grey; padding-top: 100px; padding-bottom:50px;">
    <div class="container px-5 my-5">
        <div class="row gx-5 align-items-center">
                <div class="rounded p-4" style="background: white; height: 600px;">
                    <h2 class="mb-4">Settings</h2>
                    <div class="d-flex align-items-start">
                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-home" type="button" role="tab"
                                        aria-controls="v-pills-home" aria-selected="true">Account Settings</button>
                            <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-profile" type="button" role="tab"
                                        aria-controls="v-pills-profile" aria-selected="false">Password</button>
                            <!-- <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-messages" type="button" role="tab"
                                        aria-controls="v-pills-messages" aria-selected="false">Messages</button>
                            <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-settings" type="button" role="tab"
                                        aria-controls="v-pills-settings" aria-selected="false">Settings</button> -->
                        </div>
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                            <?php 
                            if (isset($_SESSION['info'])) { 
                            echo $_SESSION['info'];
                            unset($_SESSION['info']);
                            }
                            ?>
                            <div class="card-body">
                            <?php
                                $query = "SELECT * FROM users_creds WHERE email = '$user_email' ";
                                $query_run = mysqli_query($conn, $query);
                                foreach($query_run as $row)
                                {
                            ?>
                                <form action="functions/profile-update.php" method="POST">
                                    <input type="hidden" name="acc_id" value="<?php echo $row['id_counter'] ?>">
                                        <div class="form-group">
                                            <label> First Name </label>
                                            <input type="text" name="first_name" id="first-input" value="<?php echo $row['first_name'] ?>" class="form-control"
                                                    placeholder="Edit First Name" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label> Middle Name </label>
                                            <input type="text" name="middle_name" id="middle-input" value="<?php echo $row['middle_name'] ?>" class="form-control"
                                                    placeholder="Edit Middle Name" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label> Last Name </label>
                                            <input type="text" name="last_name" id="last-input" value="<?php echo $row['last_name'] ?>" class="form-control"
                                                    placeholder="Edit Last Name" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label> Phone Number </label>
                                            <input type="text" name="phone_number" id="number-input" value="<?php echo $row['phone_number'] ?>" class="form-control"
                                                    placeholder="Edit Phone Number" disabled>
                                        </div>
                                            <div class="btn btn-primary" id="editButton"> Edit Profile </div>
                                            <div class="btn btn-danger" style="display: none;" id="cancelButton"> CANCEL </div>
                                        <button type="submit" name="acc_update_btn"  class="btn btn-info" style="display: none;" id="updateButton"> Update </button>
                                </form>
                            <?php
                                }
                            ?> 
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                            <div class="card-body">
                            <?php
                                $pass_query = "SELECT * FROM users_creds WHERE email = '$user_email' ";
                                $pass_query_run = mysqli_query($conn, $pass_query);
                                foreach($pass_query_run as $new_row)
                                {
                            ?>
                                <form action="functions/password-update.php" method="POST">
                                    <input type="hidden" name="pass_id" value="<?php echo $row['id_counter'] ?>">
                                        <div class="form-group">
                                            <label> OLD PASSWORD</label>
                                            <input type="password" name="old_password" id="old_password" value="" class="form-control"
                                                    placeholder="Enter Old Password" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label> NEW PASSWORD </label>
                                            <input type="password" name="new_password" id="new_password" value="" class="form-control"
                                                    placeholder="Enter New Password" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label> CONFIRM PASSWORD </label>
                                            <input type="password" name="confirm_password" id="confirm_password" value="" class="form-control"
                                                    placeholder="Enter Confirm Password" disabled>
                                        </div>
                                            <div class="btn btn-primary" id="pass_editButton"> Edit Profile </div>
                                            <div class="btn btn-danger" style="display: none;" id="pass_cancelButton"> CANCEL </div>
                                        <button type="submit" name="pass_update_btn"  class="btn btn-info" style="display: none;" id="pass_updateButton"> Update </button>
                                </form>
                                <?php
                                }
                            ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                                    Sed kasd kasd ea clita sed lorem amet tempor est voluptua, labore stet dolores gubergren clita lorem sed nonumy at. Dolores et ut erat voluptua. Est voluptua stet accusam rebum, elitr amet sit takimata sea eirmod. Sanctus elitr amet sit dolore sea stet et ut. Dolor et sanctus elitr ut.
                            </div>
                            <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                Sit et vero kasd sea et at, aliquyam takimata et et est, labore et takimata sed ut stet sanctus, nonumy dolor invidunt sit labore et, amet et dolor sit dolor tempor et dolor ipsum nonumy, accusam clita sadipscing ut et labore labore est, dolore accusam vero at est sit. Invidunt.
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
<?php include ('includes/footer.php'); ?>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script> -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const editButton = document.getElementById("editButton");
        const cancelButton = document.getElementById("cancelButton");
        const updateButton = document.getElementById("updateButton");
        const first_name = document.getElementById("first-input");
        const middle_name = document.getElementById("middle-input");
        const last_name = document.getElementById("last-input");
        const phone_number = document.getElementById("number-input");

        const passeditButton = document.getElementById("pass_editButton");
        const passcancelButton = document.getElementById("pass_cancelButton");
        const passupdateButton = document.getElementById("pass_updateButton");
        const old_password = document.getElementById("old_password");
        const new_password = document.getElementById("new_password");
        const confirm_password = document.getElementById("confirm_password");

        editButton.addEventListener("click", function() {
            cancelButton.style.display = "inline-block";
            updateButton.style.display = "inline-block";
            editButton.style.display = "none";
            first_name.removeAttribute("disabled");
            middle_name.removeAttribute("disabled");
            last_name.removeAttribute("disabled");
            phone_number.removeAttribute("disabled");
        });
        cancelButton.addEventListener("click", function() {
            cancelButton.style.display = "none";
            updateButton.style.display = "none";
            editButton.style.display = "inline-block";
            first_name.setAttribute("disabled","disabled");
            middle_name.setAttribute("disabled","disabled");
            last_name.setAttribute("disabled","disabled");
            phone_number.setAttribute("disabled","disabled");
        });
        passeditButton.addEventListener("click", function() {
            passcancelButton.style.display = "inline-block";
            passupdateButton.style.display = "inline-block";
            passeditButton.style.display = "none";
            old_password.removeAttribute("disabled");
            new_password.removeAttribute("disabled");
            confirm_password.removeAttribute("disabled");
        });
        passcancelButton.addEventListener("click", function() {
            passcancelButton.style.display = "none";
            passupdateButton.style.display = "none";
            passeditButton.style.display = "inline-block";
            old_password.setAttribute("disabled","disabled");
            new_password.setAttribute("disabled","disabled");
            confirm_password.setAttribute("disabled","disabled");
        });
    });
</script>
</body>
</html>
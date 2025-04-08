<?php
include('includes/header.php');
include('includes/navbar.php');

?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"> Change Password </h6>
        </div>
        <?php
        if (isset($_SESSION['info'])) {
            echo $_SESSION['info'];
            unset($_SESSION['info']);
        }
        ?>
        <div class="card-body">
            <form action="" method="POST">
                <div class="form-group">
                    <label for="oldpword-input"> Old Password </label>
                    <div class="input-group pt-2">
                        <input type="password" name="old_pword" id="oldpword-input" value="" class="form-control col-sm-4" placeholder="Enter Old Password">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-eye-slash" id="toggleOldPassword"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group pt-4">
                    <label for="newpword-input">New Password</label>
                    <div class="input-group pt-2">
                        <input type="password" name="new_pword" id="newpword-input" value="" class="form-control col-sm-4" placeholder="Enter New Password">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-eye-slash" id="toggleNewPassword"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group pt-2 pb-5">
                    <div class="input-group">
                        <input type="password" name="rpt_new_pword" id="rpt-newpword-input" value="" class="form-control col-sm-4" placeholder="Repeat New Password">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-eye-slash" id="toggleRepeatPassword"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <button type="" name="cncl_btn" class="btn btn-danger" id="cancelButton"> CANCEL </button>
                <button type="submit" name="psswrd_update_btn" class="btn btn-info" id="updateButton"> Update </button>
            </form>

        </div>
    </div>
</div>
<?php
include('includes/footer.php');
include('includes/script.php');
?>

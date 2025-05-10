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
?>
<div class="container-fluid ">
    <!-- <div class="col-sm-12 col-xl-6 justify-content-start"></div> -->
    <h1 class="h3 mb-2 text-gray-800">Create News</h1>
    <div class="col-sm-12 col-xl-12 justify-content-start">
        <div class="card shadow ">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Please fill up all required fields</h6>
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
                                    <!-- <form id="submit_news_form" name="submit_news_form" method="POST" action="news/create-news.php"> -->
                                    <form id="submit_news_form" name="submit_news_form" method="POST" role="form" action="">
                                        <div class="row">
                                            <div class="col-xl-12">
                                               <center>
                                                    <img src="https://rb.gy/ahvfma" id="news-display-image" style="height: 250px; width: 250px;">
                                               </center> 
                                                <label for="news-subject"><b>Image</b>
                                                    <span style="color: red;">*</span>
                                                </label>
                                            </div> <br> <br>
                                            <div class="col-xl-12">
                                                <input type="file" class="name" name="news-image" id="news-image" accept="image/*" onchange="document.getElementById('news-display-image').src = window.URL.createObjectURL(this.files[0])" required>
                                            </div>
                                            <div class="col-xl-6">
                                                <label for="news-subject"><b>Subject</b>
                                                    <span style="color: red;">*</span>
                                                </label>
                                                <input type="text" class="name" name="news-subject" id="news-subject" placeholder="Enter Subject" required>
                                            </div>
                                            <div class="col-xl-6">
                                                <label for="news-subject"><b>Other Details</b>
                                                    <span style="color: red;">*</span>
                                                </label>
                                                <input type="text" class="name" name="news-other-details" id="news-other-details" placeholder="Enter Other Details" required>
                                            </div>
                                            <div class="col-xl-12">
                                                <label for="news-subject"><b>Description</b>
                                                    <span style="color: red;">*</span>
                                                </label>
                                                <textarea type="text" class="name" name="news-description" id="news-description" placeholder="Description" required></textarea>
                                            </div>
                                        </div>
                                        <button type="button" name="submit_form" id="btn-submit-news" class="btn-primary mt-3 mb-4" type="submit_frm" disabled>Submit</button>
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

    <div class="modal fade" id="modal-loading" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-sm modal-spinner">
            <center> <img class="spinner" /> <p class="m-0 font-weight-bold text-white">Submitting and uploading data...</p> </center>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
    <script src="https://www.gstatic.com/firebasejs/6.4.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/6.4.2/firebase-firestore.js"></script>
    <script src="https://www.gstatic.com/firebasejs/6.4.2/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/6.4.2/firebase-storage.js"></script>
    <script src="js/create-news.js"></script>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../image-upload/user-images/plugins/toastr/toastr.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- <script src="plugins/jquery/jquery.min.js"></script> -->
    <!-- <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script> -->
</body>

</html>
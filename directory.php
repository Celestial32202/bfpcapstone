<?php
include('includes/header.php');
include('includes/navbar.php');

?>

<section id="contact" class="contact pt-5">
    <div class="container pt-5">
        <div class="section-header">
            <h2> </h2>
        </div>
    </div>
    <div class="container">
        <div class="row gy-5 gx-lg-5">
            <div class="col-lg-12 mb-5 d-flex justify-content-center align-items-center">
                <img class="img-fluid  fixed-main-size" src="img/bfp-directory-1.png" style="">
            </div>
            <div class="col-lg-12 mb-5 d-flex justify-content-center align-items-center">
                <img class="img-fluid  fixed-main-size" src="img/bfp-directory-2.png" style="">
            </div>
        </div>
    </div>
</section>

<script src="https://www.gstatic.com/firebasejs/6.4.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.4.2/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.4.2/firebase-auth.js"></script>

<script src="assets/js/global.js"></script>
<script src="assets/js/config.js"></script>
<script src="assets/js/user-request.js"> </script>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>
<?php
require_once 'config.php'; // Ensures database connection file is included only once
include('includes/header.php');
include('includes/navbar.php');
?>

<section id="contact" class="contact pt-5">
    
    <div class="container pt-5">
        <div class="section-header">
            <h2>News Articles</h2>
        </div>
    </div>

    <div class="container">

        <div class="row">
            <?php
                $query = "SELECT * FROM news";
                $query_run = mysqli_query($conn, $query);
                
            if (mysqli_num_rows($query_run) > 0) {
                while ($row = mysqli_fetch_assoc($query_run)) {
            ?> <br>
            <div class="col-sm-6 col-md-6 border border-primary">
                <div class="thumbnail"> <br>
                    <center>
                        <img src="<?php echo $row['news_image']; ?>" alt="https://rb.gy/ahvfma" style="width: 150px; height: 150px;">
                    </center>
                    <div class="caption">
                        <h3 class="text-center" id="news-subject"><?php echo $row['news_subject']; ?>"</h3>
                        <h5 class="text-center" id="news-description"><?php echo $row['news_description']; ?></h3>
                        <center>
                            <p class="bg-primary text-white text-wrap"
                                style="width: 150px; padding: 10px;" id="news-other-details">
                                <?php echo $row['news_other_details']; ?>
                            </p>
                        </center>
                    </div>
                </div>
            </div>
            <?php } } ?>
        </div>
        <!-- <div class="row gy-5 gx-lg-5">
            <div class="col-lg-12 mb-5 d-flex justify-content-center align-items-center">
                <iframe src="https://dromic.dswd.gov.ph/category/situation-reports/2024/fire-2024/" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen allow="autoplay" style="overflow:hidden;height:100vh;width:200vh" height="150vh" width="250vh"></iframe>
            </div>
        </div> -->
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
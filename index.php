<?php
include('includes/header.php');
include('includes/navbar.php');
?>
<!-- <header class="bg-dark py-5">
    <div class=" px-5 mt-5">
        <div class="row gx-5 align-items-center justify-content-center">
            <div class="col-lg-12 col-xl-12 col-xxl-6">
                <div id="map"></div>
            </div>

        </div>
    </div>
</header> -->

<header class="bg-dark py-5">
    <div class=" px-5 mt-5">
        <div class="landing-page-row row gx-5 align-items-center justify-content-center">
            <div class="col-lg-6 col-xl-5 col-xxl-5">
                <div class="my-5 text-center text-xl-start">
                    <h1 class="display-4 fw-bolder text-white mb-5">Welcome to Bureau of Fire Protection Taguig</h1>
                    <p class="lead fw-normal text-white-50 mb-5">Your quick action in reporting the fire can save
                        lives and further damage. Stay calm, provide clear details, and know that help is on the way!
                    </p>
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center justify-content-xl-start">
                        <?php
                        if (isset($_SESSION['SESSION_EMAIL'])) {
                            echo '<a class="btn btn-homepage btn-lg px-4 me-sm-3" href="#">Map!</a>';
                        } else {
                            echo '<a class="btn btn-homepage btn-lg px-4 me-sm-3" href="fire-incident-form.php">Report Fire Incident</a>';
                        }
                        ?>
                        <a class="btn btn-outline-light btn-lg px-4" href="#">Interactive Map</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-7 col-xxl-7 text-center">
                <div id="map"></div>
            </div>
        </div>
    </div>
</header>

<script src="test.js"></script>
<?php
include('includes/footer.php');
include('includes/scripts.php');
?>
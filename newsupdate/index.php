<?php
$currentPage = 'home-page';
include('includes/header.php');
include('includes/navbar.php');
?>
<header class="bg-dark py-5">
    <div class=" px-5 mt-5">
        <div class="row gx-5 align-items-center justify-content-center">
            <div class="col-lg-8 col-xl-7 col-xxl-6">
                <div class="my-5 text-center text-xl-start">
                    <h1 class="display-5 fw-bolder text-white mb-2">Welcome to Sangguniang Kabataan ng Barangay
                        Magtanggol!</h1>
                    <p class="lead fw-normal text-white-50 mb-4">Join us in shaping the future of our youth community by
                        participating in our confidential survey. Your input will help us create meaningful plans and
                        projects for the Batang Magtanggol Youth Organization.</p>
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center justify-content-xl-start">
                        <?php
                        if (isset($_SESSION['SESSION_EMAIL'])) {
                            echo '<a class="btn btn-homepage btn-lg px-4 me-sm-3" href="katipunan-form.php">Join Now!</a>';
                        } else {
                            echo '<a class="btn btn-homepage btn-lg px-4 me-sm-3" href="forms/loginform.php">Join Now!</a>';
                        }
                        ?>
                        <a class="btn btn-outline-light btn-lg px-4" href="about-us.php">About Us</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-xxl-6 d-none d-xl-block text-center">
                <img class="img-fluid rounded-3 my-5" src="images/magtanggol.jpg" alt="..." />
            </div>
        </div>
    </div>
</header>
<section class="about_section layout_padding long_section bg-color">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="img-box">
                    <img src="images/about-us.jpg" alt="">
                </div>
            </div>
            <div class="col-md-6">
                <div class="detail-box">
                    <div class="heading_container">
                        <h2>
                            About Us
                        </h2>
                    </div>
                    <p>
                        Our vision is to cultivate an exemplary youth community, where inclusivity, integrity, and
                        leadership flourish. Through a variety of programs spanning education, sports, health, social
                        inclusion, and environmental sustainability, we're dedicated to empowering the vibrant youth of
                        Barangay Magtanggol. Join us as we work towards a brighter future for our community and our
                        country.
                    </p>
                    <a href="">Read More</a>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="container-fluid bg-color" style="background-color: #fff4dc; padding-top: 30px;">
    <div class="container">
        <div class="row ">
            <div class="col-lg-12 ">
                <div class="row news-border">
                    <div class="col-12">
                        <div class="section-title">
                            <h1 class="m-0 text-uppercase font-weight-bold">Latest Updates</h1>
                            <a class="text-secondary font-weight-medium text-decoration-none"
                                href="rcnt-update-page.php">
                                <h5>View All</h5>
                            </a>
                        </div>
                    </div>
                    <?php
                    $count = 0;
                    while ($row = mysqli_fetch_assoc($updates)) {
                        if ($count % $updatesPerPage == 0) {
                            // Display the first update separately
                    ?>
                    <div class="col-lg-6">
                        <div class="position-relative mb-5">
                            <?php
                                    $images = json_decode($row['update_img'], true);
                                    if (!empty($images)) :
                                    ?>
                            <img class="img-fluid  fixed-main-size" src="admin/update-img/<?php echo $images[0]; ?>"
                                style="object-fit: cover; margin-bottom: 10px;">
                            <?php else : ?>
                            <img class="img-fluid w-100" src="images/default-img.jpg" style="object-fit: cover;">
                            <?php endif; ?>
                            <div class="bg-white border border-top-0 p-4">
                                <div class="mb-2">
                                    <a class="badge badge-primary text-uppercase font-weight-semi-bold p-2 mr-2"
                                        href="rcnt-update-page.php">Updates</a>
                                    <a class="text-body" href="admin/update-img/<?php echo $images[0]; ?>"><small>
                                            <?php echo date('F j, Y', strtotime($row['update_date'])); ?></small></a>
                                </div>
                                <a class="h4 d-block mb-3 text-secondary text-uppercase font-weight-bold"
                                    href="spec-update.php?url=<?php echo $row['update_num'] ?>">
                                    <?php echo $row['update_title'] ?></a>
                                <!-- <p class="m-0">Dolor lorem eos dolor duo et eirmod sea. Dolor sit magna
                                        rebum clita rebum dolor stet amet justo</p> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 align-items-center">
                        <?php
                        } else {
                            // Display the rest of the updates in a loop
                            ?>
                        <div class="d-flex align-items-center bg-white mb-3" style="height: 110px;">
                            <?php
                                    $another_images = json_decode($row['update_img'], true);
                                    if (!empty($another_images)) :
                                    ?>
                            <img class="img-fluid fixed-size" src="admin/update-img/<?php echo $another_images[0]; ?>"
                                alt="">
                            <?php else : ?>
                            <!-- Display a default image if no images are available -->
                            <img class="img-fluid w-100" src="images/default-img.jpg" style="object-fit: cover;">
                            <?php endif; ?>
                            <!-- <img class="img-fluid fixed-size" src="images/biznews-img/skmeeting.jpg" alt=""> -->
                            <div
                                class="w-100 h-100 px-3 d-flex flex-column justify-content-center border border-left-0">
                                <div class="mb-2">
                                    <a class="badge badge-primary text-uppercase font-weight-semi-bold p-1 mr-2"
                                        href="rcnt-update-page.php">Updates</a>
                                    <a class="text-body" href="admin/update-img/<?php echo $images[0]; ?>"><small>
                                            <?php echo date('F j, Y', strtotime($row['update_date'])); ?></small></a>
                                </div>
                                <a class="h6 m-0 text-secondary text-uppercase font-weight-bold"
                                    href="spec-update.php?url=<?php echo $row['update_num'] ?>">
                                    <?php echo $row['update_title'] ?></a>
                            </div>
                        </div>
                        <?php
                        }
                        $count++;
                    }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section id="services" class="my-5 ">
    <div class="container pt-5">
        <h4 class=" fw-bold display-5 mb-4">EMERGENCY HOTLINES</h4>
        <div class="row">
            <div class=" mt-4 col-md-6 col-lg-3">
                <div class="services-components text-center py-5 px-3">
                    <iconify-icon class="services-icon " icon="mdi:phone-outline"></iconify-icon>
                    <h5 class="services-heading mb-3">PATEROS POLICE</h5>
                    <p>8875-8596
                    </p>
                    <p>0998-598-7934
                    </p>

                </div>
            </div>
            <div class=" mt-4 col-md-6 col-lg-3">
                <div class="services-components text-center py-5 px-3">
                    <iconify-icon class="services-icon " icon="mdi:phone-outline"></iconify-icon>
                    <h5 class="services-heading mb-3">PATEROS RESCUE UNIT</h5>
                    <p>8642-5159
                    </p>
                    <p>0995-130-0075
                    </p>

                </div>
            </div>
            <div class=" mt-4 col-md-6 col-lg-3">
                <div class="services-components text-center py-5 px-3">
                    <iconify-icon class="services-icon " icon="mdi:phone-outline"></iconify-icon>
                    <h5 class="services-heading mb-3">PATEROS FIRE STATION</h5>
                    <p>8641-1365
                    </p>
                    <p>0917-172-8577
                    </p>
                </div>
            </div>
            <div class=" mt-4 col-md-6 col-lg-3">
                <div class="services-components text-center py-5 px-3">
                    <iconify-icon class="services-icon " icon="mdi:phone-outline"></iconify-icon>
                    <h5 class="services-heading mb-3">PATEROS FIL-CHI FIRE RESCUE VOLUNTEEERS</h5>
                    <p>8640-0229
                    </p>
                    <p>0918-438-7777
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>
<?php include('includes/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.7/dist/iconify-icon.min.js"></script>
</body>

</html>
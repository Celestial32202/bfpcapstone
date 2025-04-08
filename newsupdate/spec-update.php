<?php
$currentPage = 'spec-update-page';
include('includes/header.php');
include('includes/navbar.php');
?>
<?php
$update_num = isset($_GET['url']) ? (int)$_GET['url'] : 0;
$selected_update = get_spec_news($update_num);
?>
<div class="container-fluid page-header  p-0" style="background-color: #fff4dc;">
    <div class="container-fluid page-header-inner py-5">
        <div class="container align-items-left">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-start text-uppercase">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Updates</li>
                </ol>
            </nav>
            <h1 class="display-3 font-weight-bold text-white mb-3 animated slideInDown">Updates</h1>
        </div>
    </div>
</div>
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-12">
            <article>
                <?php
                if ($display = mysqli_fetch_assoc($selected_update)) {
                ?>
                    <header class="mb-4">
                        <h1 class="fw-bolder mb-1"><?php echo htmlspecialchars($display['update_title']); ?></h1>
                        <div class="text-muted fst-italic mb-2">Posted on <?php echo date('F j, Y', strtotime($display['update_date'])); ?></div>
                    </header>
                    <?php
                    $images = json_decode($display['update_img'], true);
                    if (!empty($images)) :
                    ?>
                        <!-- <div class="fixed-spec-size"> -->
                        <figure class="mb-4 fixed-spec-size"><img class="img-fluid rounded " src="admin/update-img/<?php echo $images[0]; ?>" alt="..." /></figure>
                        <!-- </div>     -->
                    <?php else : ?>
                        <!-- Display a default image if no images are available -->
                        <img class="img-fluid w-100" src="images/default-img.jpg" style="object-fit: cover;">
                    <?php endif; ?>
                    <section class="mb-5">
                        <div class="fs-5 mb-4"><?php echo $display['update_desc']; ?></div>
                    </section>
                <?php
                }
                ?>
            </article>
        </div>
    </div>
</div>
<div id="footer" class="footer">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-6 col-md-6 footer-info">
                <a href="index.html" class="logo d-flex align-items-center">
                    <span>Sangguniang Kabataan ng Magtanggol</span>
                </a>
                <p> Our vision is to cultivate an exemplary youth community, where inclusivity, integrity, and leadership flourish. Through a variety of programs spanning education, sports, health, social inclusion, and environmental sustainability, we're dedicated to empowering the vibrant youth of Barangay Magtanggol. Join us as we work towards a brighter future for our community and our country.</p>
                <div class="social-links d-flex mt-4">
                    <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-3 footer-links">
                <h4>Useful Links</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="rcnt-update-page.php">Updates</a></li>
                    <li><a href="about-us.php">About</a></li>
                    <li><a href="directory-page.php">Directory</a></li>
                    <li><a href="contage-page.php">Contact Us</a></li>
                    <li><a href="terms-condi.php">Terms of service</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-3 footer-contact text-center text-md-start">
                <h4>Contact Us</h4>
                <p>
                    <strong>Location:</strong> F.C. Cruz Street, Pateros, Philippines<br><br>
                    <strong>Phone:</strong> (0912) - 345 - 6789 <br><br>
                    <strong>Email:</strong> magtanggolsk@gmail.com <br>
                </p>
            </div>
        </div>
    </div>
</div>
<?
include('includes/footer.php');
?>
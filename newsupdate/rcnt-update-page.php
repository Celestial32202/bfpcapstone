<?php 
$currentPage = 'update-page';
include ('includes/header.php');
include ('includes/navbar.php');

$updatesPerPage = 6;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $updatesPerPage;
$updates = get_updates($offset, $updatesPerPage);
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
    <div class="container-fluid" style="background-color: #fff4dc; padding-top: 50px; padding-bottom:50px;">
        <div class="container ">
            <div class="row ">
                <div class="col-lg-12 ">
                    <div class="row news-border">
                        <div class="col-12">
                            <div class="section-title">
                                <h1 class="m-0 text-uppercase font-weight-bold">Latest Updates</h1>
                            </div>
                        </div>
                        <?php
                        $count = 0;
                        while ($row = mysqli_fetch_assoc($updates)) {
                        if ($count % $updatesPerPage == 0) {
                          
                        ?>
                        <div class="col-lg-6">
                            <div class="position-relative mb-5">
                                <?php
                                $images = json_decode($row['update_img'], true);
                                if (!empty($images)):
                                ?>
                                    <img class="img-fluid  fixed-main-size" src="admin/update-img/<?php echo $images[0]; ?>" 
                                    style="object-fit: cover; margin-bottom: 10px;">
                                <?php else: ?>
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
                                    href="spec-update.php?url=<?php echo $row['update_num']?>">
                                    <?php echo $row['update_title']?></a>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 align-items-center">
                            <?php
                                } else 
                                {
                            ?>
                            <div class="d-flex align-items-center bg-white mb-3" style="height: 110px;">
                                <?php
                                $another_images = json_decode($row['update_img'], true);
                                if (!empty($another_images)):
                                ?>   
                                    <img class="img-fluid fixed-size" src="admin/update-img/<?php echo $another_images[0]; ?>" alt="">
                                <?php else: ?>
                                   
                                    <img class="img-fluid w-100" src="images/default-img.jpg" style="object-fit: cover;">
                                <?php endif; ?>
                                <div class="w-100 h-100 px-3 d-flex flex-column justify-content-center border border-left-0">
                                    <div class="mb-2">
                                        <a class="badge badge-primary text-uppercase font-weight-semi-bold p-1 mr-2" href="rcnt-update-page.php">Updates</a>
                                        <a class="text-body" href="admin/update-img/<?php echo $images[0]; ?>"><small>
                                        <?php echo date('F j, Y', strtotime($row['update_date'])); ?></small></a>
                                    </div>
                                    <a class="h6 m-0 text-secondary text-uppercase font-weight-bold" 
                                    href="spec-update.php?url=<?php echo $row['update_num']?>">
                                    <?php echo $row['update_title']?></a>
                                </div>
                            </div>
                            <?php
                                }
                                $count++;
                            }
                            ?>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <ul class="pagination justify-content-center ">
                                        <?php
                                        $total_pages_query = "SELECT COUNT(*) AS count FROM news_updates WHERE update_active_stat = 1";
                                        $result = mysqli_query($conn, $total_pages_query);
                                        $total_rows = mysqli_fetch_assoc($result)['count'];
                                        $total_pages = ceil($total_rows / $updatesPerPage);

                                        for ($i = 1; $i <= $total_pages; $i++) {
                                            echo "<li class='page-item'><a class='page-link' href='?page=" . $i . "'>" . $i . "</a></li>";
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include ('includes/footer.php'); ?>
</body>
</html>
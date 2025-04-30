<header id="header" class="header fixed-top shadow" data-scrollto-offset="0">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo d-flex align-items-center scrollto me-auto me-lg-0">
            <img class="img-fluid fixed-main-size" src="img/image_logo.png" style="margin-left: 20px;">
        </a>
        <nav id="navbar" class="navbar">
            <ul>
                <li>
                    <a href="index.php" class="">Home</a>
                    <?php
                    // if (isset($_GET['active_page'] == "home_page")) {
                    //     echo '<a href="index.php?active_page=home_page" class="active">Home</a>';
                    // } else {
                    //     echo '<a href="index.php?active_page=home_page" class="">Home</a>';
                    // }
                    ?>
                </li>
                <li>
                    <a href="news-articles.php" class="">News Articles</a>
                </li>
                <!-- <li>
                    <a href="response-history.php" class="">Response History</a>
                </li> -->
                <li>
                    <a href="directory.php" class="">Directory</a>
                </li>
            </ul>
            <i class="bi bi-list mobile-nav-toggle d-none"></i>
        </nav>
        <a href="fire-incident-form.php" class="btn-login scrollto">Report Fire Incident</a>
    </div>
</header>
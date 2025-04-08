<header id="header" class="header fixed-top shadow" data-scrollto-offset="0">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    <a href="index.html" class="logo d-flex align-items-center scrollto me-auto me-lg-0">
      <img src="images/magtanggo-logo.jpg" alt="logo image">
    </a>
    <nav id="navbar" class="navbar ">
      <ul>
        <li><a href="index.php" class="<?php echo ($currentPage == 'home-page') ? 'active' : ''; ?>">Home</a></li>
        <li><a class=" <?php echo ($currentPage == 'update-page') ? 'active' : ''; ?>" href="rcnt-update-page.php">Updates</a></li>
        <li><a class=" <?php echo ($currentPage == 'about-page') ? 'active' : ''; ?>" href="about-us.php">About</a></li>
        <li><a class=" <?php echo ($currentPage == 'contact-page') ? 'active' : ''; ?>" href="contact-page.php">Contact Us</a></li>
        <li><a class=" <?php echo ($currentPage == 'directory-page') ? 'active' : ''; ?>" href="directory-page.php">Directory</a></li>
        <?php
        if (isset($_SESSION['SESSION_EMAIL'])) {
          echo '<li><a class="' . (($currentPage == 'notif-page') ? 'active' : '') . '" href="notif-page.php">Notification</a></li>';
        }
        ?>
      </ul>
      <i class="bi bi-list mobile-nav-toggle d-none"></i>
    </nav>
    <?php
    if (isset($_SESSION['SESSION_EMAIL'])) {
      echo
      '<div class="dropdown">
                  <button class="btn dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="bi bi-person-circle"></i>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                      <li><a class="dropdown-item" href="user-profile.php">Settings</a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                  </ul>';
    } else {
      echo '<a class="btn-login scrollto" href="forms/loginform.php">LOGIN</a>';
    }
    ?>
  </div>
  </div>
</header>
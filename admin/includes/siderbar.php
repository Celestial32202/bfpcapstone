<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
        <div class="sidebar-brand-text mx-3"> <?php echo $_SESSION['position'] ?></div>
    </a>
    <hr class="sidebar-divider my-0">
    <?php if (isset($_SESSION['permissions']['main_dashboard']) && $_SESSION['permissions']['main_dashboard'] == 1) { ?>
    <li class="nav-item active">
        <a class="nav-link" href="dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <?php
    }
    ?>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Interface
    </div>
    <?php if (isset($_SESSION['permissions']['manage_reports']) && $_SESSION['permissions']['manage_reports'] == 1) { ?>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReports"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Incident Reports</span>
        </a>
        <div id="collapseReports" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Report Tables:</h6>
                <a class="collapse-item" href="pending-reports.php">Incident Report</a>
                <a class="collapse-item" href="approved-reports.php">Approved Reports</a>
                <a class="collapse-item" href="declined-reports.php">Declined Reports</a>
            </div>
        </div>
    </li>
    <?php
    }
    ?>
    <?php if (isset($_SESSION['permissions']['recieve_rescue_reports']) && $_SESSION['permissions']['recieve_rescue_reports'] == 1) { ?>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFireRescue"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Fire Rescue Lists</span>
        </a>
        <div id="collapseFireRescue" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Fire Rescue Tables</h6>
                <a class="collapse-item" href="ong-rescues.php">Ongoing Rescue Details</a>
                <a class="collapse-item" href="#">Finished Rescues</a>
            </div>
        </div>
    </li>
    <?php
    }
    ?>
    <?php if (isset($_SESSION['permissions']['monitor_rescue']) && $_SESSION['permissions']['monitor_rescue'] == 1) { ?>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRescue" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Rescue Monitoring</span>
        </a>
        <div id="collapseRescue" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Rescue Tables</h6>
                <a class="collapse-item" href="pending-reports.php">Incident Report</a>
                <a class="collapse-item" href="approved-reports.php">Approved Reports</a>
            </div>
        </div>
    </li>
    <?php
    }
    ?>


    <?php if (isset($_SESSION['permissions']['manage_reports']) && $_SESSION['permissions']['manage_reports'] == 1) { ?>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Public Posts
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseNews" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-newspaper"></i>
            <span>News Section</span>
        </a>
        <div id="collapseNews" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Public Posts</h6>
                <a class="collapse-item" href="#">Post News</a>
                <a class="collapse-item" href="#">List of Posted News</a>
                <a class="collapse-item" href="#">Submitted News</a>
            </div>
        </div>
    </li>
    <?php
    }
    ?>

    <?php if (isset($_SESSION['permissions']['manage_accounts']) && $_SESSION['permissions']['manage_accounts'] == 1) { ?>
    <hr class="sidebar-divider">
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Admin Options</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Options</h6>
                <a class="collapse-item" href="#">Manage Admins</a>
                <a class="collapse-item" href="add-admin-acc.php">Add Account</a>
            </div>
        </div>
    </li>
    <?php
    }
    ?>
    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->
<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
<?php include('includes/header.php'); ?>
<!-- Sidebar -->
<?php include('includes/siderbar.php'); ?>
<!-- End of Sidebar -->
<!-- Topbar -->
<?php include('includes/navbar.php'); ?>
<!-- End of Topbar -->
<?php
if (!isset($_SESSION['permissions']['manage_reports']) && $_SESSION['permissions']['manage_reports'] != 1) {
    header("Location: dashboard.php");
    exit();
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h4 class="h3 mb-0 text-gray-800">User's Video</h4>
    </div>
    <div class="row">
        <div class="col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <div class="video-loading mt-5" id="video-loading" style="display:block;">
                        <div class="spinner"></div>
                        <h4 class="mt-5">User video is loading...
                        </h4>
                    </div>
                    <div class="video-reconnecting mt-5" id="video-reconnecting" style="display:none;">
                        <div class="spinner"></div>
                        <h4 class="mt-5">User video is reconnecting...
                        </h4>
                    </div>
                    <div class="user-disconnected" id="user-disconnected" style="display:none;">
                        <div class="spinner"></div>
                        <h4 class="mt-4">User call disconnected, call window closing in <span id="countdown">5</span>...
                        </h4>
                    </div>
                    <div class="user-endcall" id="user-endcall" style="display:none;">
                        <div class="spinner"></div>
                        <h4 class="mt-4">User ended call, call window closing in <span id="countdown">5</span>...</h4>
                    </div>
                    <div id="videoContainer" style="display: none;">
                        <div id="adminVideoContainer" style="display: none;">
                            <video id="adminVideo" autoplay playsinline></video>
                        </div>
                        <button class="mt-4 btn-info info" id="playButton">Play Video</button>
                        <button class="mt-4 btn-danger btn" id="endCallBtn" onclick="endCall()">X End Call</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
<?php include('includes/footer.php'); ?>
<script src="js/call.js"></script>
<?php include('includes/scripts.php'); ?>
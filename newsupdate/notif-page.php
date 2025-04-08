<?php
$currentPage = 'notif-page';
include('includes/header.php');
include('includes/navbar.php');

if (!isset($_SESSION['SESSION_EMAIL'])) {
    header('Location: index.php');
}else{
    $notificationsPerPage = 5;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $offset = ($page - 1) * $notificationsPerPage;
    $totalNotifications = get_total_notifications();
    $totalPages = ceil($totalNotifications / $notificationsPerPage);
    $notifications = get_notifications($offset, $notificationsPerPage);
}
?>
<section class="section-50">
    <div class="container">
        <h3 class="m-b-50 heading-line">
            Notifications <i class="fa fa-bell text-muted"></i>
        </h3>
        <div class="notification-ui_dd-content">
            <?php if (!empty($notifications)) : ?>
                <?php foreach ($notifications as $notification) : ?>
                    <div class="notification-list notification-list--unread">
                        <div class="notification-list_content">
                            <div class="notification-list_detail">
                                <p><b><?php echo htmlspecialchars($notification['notif_title']); ?></b></p>
                                <p class="text-muted">
                                    <?php echo htmlspecialchars($notification['content_title']); ?></p>
                                </p>
                                <p class="text-muted"><small><?php echo date('F j, Y', strtotime($notification['notif_time'])); ?></small></p>
                            </div>
                        </div>
                        <div class="notification-list_feature-img">
                            <?php
                            $images = json_decode($notification['notif_img'], true);
                            if (!empty($images)) :
                            ?>
                                <img src="admin/update-img/<?php echo $images[0]; ?>" alt="Feature image" />
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endforeach; ?>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="pagination justify-content-center">
                        <?php
                        $total_pages_query = "SELECT COUNT(*) AS count FROM notify_all";
                        $result = mysqli_query($conn, $total_pages_query);
                        $total_rows = mysqli_fetch_assoc($result)['count'];
                        $total_pages = ceil($total_rows / $notificationsPerPage);

                        for ($i = 1; $i <= $total_pages; $i++) {
                            echo "<li class='page-item'><a class='page-link' href='?page=" . $i . "'>" . $i . "</a></li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php else : ?>
        <p>No notifications found.</p>
    <?php endif; ?>
    <!-- <div class="text-center">
            <a href="#!" class="dark-link">Load more activity</a>
        </div> -->
    </div>
</section>

<?php
include('includes/footer.php');
?>
</body>

</html>
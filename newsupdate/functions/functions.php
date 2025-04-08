<?php
    require_once 'config.php';

    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    function get_updates($offset, $limit)
    {
        global $conn;
        $query = "SELECT * FROM news_updates WHERE update_active_stat = 1 ORDER BY updates_id DESC LIMIT ?, ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ii", $offset, $limit);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }
    function get_spec_news($update_num){
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM news_updates WHERE update_num = ?");
        $stmt->bind_param("i", $update_num);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }
    function get_total_notifications() {
        global $conn;
        $query = "SELECT COUNT(*) as total FROM notify_all";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }
        function get_notifications($offset, $limit) {
            global $conn;
            $query = "SELECT * FROM notify_all ORDER BY notif_counter DESC LIMIT ?, ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $offset, $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            $notifications = [];
            while ($row = $result->fetch_assoc()) {
                $notifications[] = $row;
            }
            return $notifications;
        }
?>

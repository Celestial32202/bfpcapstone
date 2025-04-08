<?php
require_once('config.php');
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
function getUserDetails() {
    global $conn;

    $sql = "SELECT * FROM users_creds";
    $result = $conn->query($sql);
    $users = [];

    if ($result->num_rows > 0) {
        // Fetch data from each row
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    $conn->close();
    return $users;
}
function getTotalUsers() {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM users_creds";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    
    return $row['count'];
}

function getTotalSurveys() {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM survey_information";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['count'];
}

function getTotalNewsUpdates() {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM news_updates WHERE update_active_stat = 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
 
    return $row['count'];
}
function get_total_updates(){
    global $conn;
    $sql = "SELECT * FROM news_updates WHERE update_active_stat = 1";
    $result = $conn->query($sql);
    $users = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    $conn->close();
    return $users;
}
function get_update_edit($update_num) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM news_updates WHERE update_num = ?");
    $stmt->bind_param("i", $update_num);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc(); // Fetching only one row since we're retrieving a specific update
}
function deactivate_post_by_id($postid) {
    global $conn;

    $sql = "UPDATE news_updates SET update_active_stat = 0 WHERE update_num = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, 'i', $postid);
    $result = mysqli_stmt_execute($stmt);

    // Check if update was successful
    if ($result) {
        return true;
    } else {
        return false;
    }
}
?>

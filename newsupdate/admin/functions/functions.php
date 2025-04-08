<?php
require_once('config.php');
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['update-title'];
    $description = $_POST['update_description'];
    $notify = isset($_POST['notify']) ? 1 : 0; 

    if (empty($title) || empty($description)) {
        echo json_encode(['response' => false, 'message' => 'All fields are required.']);
        exit;
    } else {
        $uploadedFiles = [];
        $uploadDirectory = '../update-img/';

        function generateRandomFileName($extension)
        {
            return uniqid() . '.' . $extension;
        }

        function generateUniqueRandomNumber($conn)
        {
            $count = 0;
            do {
                $randomNumber = rand(10000000, 99999999);
                $query = "SELECT COUNT(*) as count FROM news_updates WHERE update_num = ?";
                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    return false; 
                }
                $stmt->bind_param("i", $randomNumber);
                $stmt->execute();
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();
            } while ($count > 0);

            return $randomNumber;
        }

        if (!empty($_FILES['upload-inputtest']['name'][0])) {
            foreach ($_FILES['upload-inputtest']['tmp_name'] as $key => $tmpName) {
                $originalFileName = $_FILES['upload-inputtest']['name'][$key];
                $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
                $randomFileName = generateRandomFileName($fileExtension);
                $targetFilePath = $uploadDirectory . $randomFileName;

                if (move_uploaded_file($tmpName, $targetFilePath)) {
                    $uploadedFiles[] = $targetFilePath;
                } else {
                    echo json_encode(['response' => false, 'message' => 'Failed to upload image.']);
                    exit;
                }
            }

            $uniqueNumber = generateUniqueRandomNumber($conn);
            if ($uniqueNumber === false) {
                echo json_encode(['response' => false, 'message' => 'Failed to generate unique number.']);
                exit;
            }
            $status = 1;
            $sql = "INSERT INTO news_updates (update_title, update_desc, update_img, update_num, update_active_stat) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                echo json_encode(['response' => false, 'message' => 'Failed to prepare statement.']);
                exit;
            } else {
                $input = json_encode($uploadedFiles);
                $stmt->bind_param("sssii", $title, $description, $input, $uniqueNumber, $status);
                $stmt->execute();
                $stmt->close();

                if ($notify == 1) {

                    $rndm_notif_id = generateUniqueRandomNumber($conn);
                    if ($rndm_notif_id === false) {
                        echo json_encode(['response' => false, 'message' => 'Failed to generate notification ID.']);
                        exit;
                    }
                    $notification_Title = "Sangguniang Kabataan has posted a new update!";
                    $notificationSql = "INSERT INTO notify_all (notif_id, notif_title, content_title, notif_img, update_id, notif_active_stat) VALUES (?, ?, ?, ?, ?, ?)";
                    $notificationStmt = $conn->prepare($notificationSql);
                    if ($notificationStmt) {
                        $notificationStmt->bind_param("isssii", $rndm_notif_id, $notification_Title, $title, $input, $uniqueNumber, $status);
                        $notificationStmt->execute();
                        $notificationStmt->close();
                    } else {
                        echo json_encode(['response' => false, 'message' => 'Failed to prepare notification statement.']);
                        exit;
                    }
                }
                echo json_encode(['response' => true, 'message' => 'Post Created Successfully.']);
                exit;
            }
        } else {
            echo json_encode(['response' => false, 'message' => 'At least one image is required.']);
            exit;
        }
    }
} else {
    echo json_encode(['response' => false, 'message' => 'Invalid request.']);
}
?>
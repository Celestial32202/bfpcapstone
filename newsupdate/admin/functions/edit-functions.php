<?php
require_once('config.php');
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
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
            
            $input = json_encode($uploadedFiles);
            $sql = "UPDATE news_updates SET update_title = ?, update_desc = ?, update_img = ? WHERE update_num = ?";
            $stmt = $conn->prepare($sql);
            
            if ($stmt === false) {
                echo json_encode(['response' => false, 'message' => 'Failed to prepare statement.']);
                exit;
            } else {
                $stmt->bind_param("sssi", $title, $description, $input, $id);
                $stmt->execute();
                $stmt->close();

                if ($notify == 1) {
                    $notification_Title = "Sangguniang Kabataan has posted a new update!";
                    $notificationSql = "UPDATE notify_all SET notif_title = ?, content_title = ?, notif_img = ? WHERE update_id = ?";
                    $notificationStmt = $conn->prepare($notificationSql);
                    if ($notificationStmt) {
                        $notificationStmt->bind_param("sssi", $notification_Title, $title, $input, $id);
                        $notificationStmt->execute();
                        $notificationStmt->close();
                    } else {
                        echo json_encode(['response' => false, 'message' => 'Failed to prepare notification statement.']);
                        exit;
                    }
                }
                echo json_encode(['response' => true, 'message' => 'Post Updated Successfully.']);
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

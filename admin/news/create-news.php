<?php
session_start();
// require_once 'email-sender.php';
require_once '../../config.php'; // Ensures database connection file is included only once
require_once '../configs/jwt_handler.php';

if (!isset($_SESSION['permissions']['manage_accounts']) && $_SESSION['permissions']['manage_accounts'] != 1) {
    header("Location: dashboard.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $image_url = filter_var($_POST["news_image_url"] ?? "", FILTER_SANITIZE_STRING);
        $subject = filter_var($_POST["news_subject"] ?? "", FILTER_SANITIZE_STRING);
        $otherDetails = filter_var($_POST["news_other_details"] ?? "", FILTER_SANITIZE_STRING);
        $description = filter_var($_POST["news_description"] ?? "", FILTER_SANITIZE_STRING);
        // $news_subject = $conn->real_escape_string($_POST['news-subject']);
        // $news_other_details = $conn->real_escape_string($_POST['news-other-details']);
        // $news_description = $conn->real_escape_string($_POST['news-description']);

        // echo "image_url ->" . $image_url;
        // echo "news_subject ->" . $news_subject;
        // echo "news_other_details ->" . $news_other_details;
        // echo "news_description ->" . $news_description;

        $stmt = $conn->prepare("INSERT INTO news (news_image, news_subject, news_other_details, news_description) VALUES (?,?,?,?)");

        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }

        $stmt->bind_param(
            "ssss",
            $image_url,
            $subject,
            $otherDetails,
            $description
        );

        if (!$stmt->execute()) {
            throw new Exception("Error inserting new admin account: " . $stmt->error);
        }

        $stmt->close();
        $_SESSION['error_message'] = "<div class='mt-3 alert alert-success'>News Added!</div>";
        header("Location: ../post-news.php");
        exit();
    } catch (Exception $e) {
        error_log("[" . date("Y-m-d H:i:s") . "] ERROR: " . $e->getMessage() . PHP_EOL, 3, '../logs/error_log.txt');
        $_SESSION['error_message'] = "<div class='mt-3 alert alert-danger'>Something went wrong. Please try again later.</div>";
        header("Location: ../post-news.php");
        exit();
    } finally {
        if (isset($conn)) {
            $conn->close();
        }
    }
}
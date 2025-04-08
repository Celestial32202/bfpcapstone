<?php
require_once('functions/call-functions.php');
session_start();

// if (!isset($_SESSION['SESSION_ADMIN'])) {
//     header('Location: index.php');
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>SKM ADMIN</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="../images/proper-logo.jpg" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Summer Note scripts -->
    <link rel="stylesheet" href="plugin/summernote/summernote-lite.css">
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="plugin/summernote/summernote-lite.js"></script>
    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <style>
    .note-editor.note-frame .note-editing-area .note-editable,
    .note-editor.note-airframe .note-editing-area .note-editable {
        background: white;
    }
    </style>
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">
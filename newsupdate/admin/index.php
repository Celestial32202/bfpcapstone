<?php
session_start();
function validateUser($email, $password)
{
    // Include database connection
    require_once('functions/config.php');
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM admin_acc WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            return true;
        }
    }
    return false;
}
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (validateUser($email, $password)) {
        $_SESSION['SESSION_EMAIL'] = $email;
        header('Location: dashboard.php');
        exit();
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ADMIN</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

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
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h3>Login</h3>

                        </div>
                        <?php if (isset($error_message)) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php
                                echo $error_message; ?>
                        </div>
                        <?php endif; ?>
                        <form action="" method="POST">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="name@example.com">
                                <label for="email">Email address</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Password" required>
                                <label for="password">Password</label>
                            </div>
                            <button type="submit" class="btn btn-primary py-3 w-100 mb-4" name="login">Login</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php
        include('includes/footer.php');
        include('includes/script.php');
        ?>
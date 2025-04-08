<?php
require_once('config.php');

// Establish database connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    http_response_code(500);
    echo json_encode(array('error' => 'Database connection failed'));
    exit;
}

if(isset($_POST['email'])) {
    $userEmail = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "SELECT * FROM users_creds WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $userEmail);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0) {
        $userDetails = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    
        $sql = "SELECT * FROM survey_information WHERE user_email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userEmail);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        if(mysqli_num_rows($result) > 0) {
            $surveyDetails = mysqli_fetch_assoc($result);
            // Combine user details and survey details into a single associative array
            $response = array(
                'user_details' => $userDetails,
                'survey_details' => $surveyDetails
            );
            http_response_code(200);
            header('Content-Type: application/json');
            // Encode the combined array into JSON
            echo json_encode($response);
        } else {
            // If there are no emails in the survey_information, return "not filled"
            http_response_code(200);
            header('Content-Type: application/json');
            $notFilledArray = array(
                'user_details' => $userDetails,
                'survey_details' => array(
                    'full_address' => 'not filled',
                    'civil_status' => 'not filled',
                    'age_group' => 'not filled',
                    'youth_class' => 'not filled',
                    'youth_class_needs' => 'not filled',
                    'work_status' => 'not filled',
                    'educ_background' => 'not filled',
                    'sk_voter' => 'not filled',
                    'registered_voter' => 'not filled'
                )
            );
            echo json_encode($notFilledArray);
        }
    } else {
        mysqli_stmt_close($stmt);
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'User not found'));
    }
} else {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Email parameter is missing'));
}

// Close database connection
mysqli_close($conn);

?>

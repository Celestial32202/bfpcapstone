<?php

$db_host_name= "localhost";
$db_name = "bfp-taguig-db";

$db_local_un = "root";
$db_local_pw = "";

$db_hosting_un= "bfp-taguig";
$db_hosting_pw = "Mar@32202";

define('DB_HOST', $db_host_name);
define('DB_NAME', $db_name);
define('DB_USER', $db_local_un);
define('DB_PASS', $db_local_pw);

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Database connection failed: " . $conn->connect_error);
}
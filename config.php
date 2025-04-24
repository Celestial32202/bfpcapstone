<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'bfp-taguig');
define('DB_PASS', 'Mar@32202');
define('DB_NAME', 'bfp-taguig-db');


$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check if the connection failed
if (!$conn) {
    die("Database connection failed: " . $conn->connect_error);
}
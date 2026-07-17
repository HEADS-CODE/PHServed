<?php

//Database connection

$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "phserved_db";

$conn = mysqli_connect(
    $db_host,
    $db_user,
    $db_password,
    $db_name
);

//Connection check
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>
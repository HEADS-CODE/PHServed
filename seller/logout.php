<?php

session_set_cookie_params(0, dirname($_SERVER["SCRIPT_NAME"]));
session_name("phserved_seller_session");
session_start();

require_once("../config/db_connect.php");

//Logout record
if (
    isset($_SESSION["user_id"]) &&
    isset($_SESSION["role"]) &&
    $_SESSION["role"] == "Admin"
) {
    $admin_id = $_SESSION["user_id"];

    $log_sql =
        "INSERT INTO audit_logs (
            user_id,
            action
        ) VALUES (
            $admin_id,
            'Logged out of the Seller System'
        )";

    mysqli_query($conn, $log_sql);
}

$_SESSION = array();

session_destroy();

header("Location: login.php");
exit;

?>
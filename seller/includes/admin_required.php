<?php

//Admin access

session_set_cookie_params(0, dirname($_SERVER["SCRIPT_NAME"]));
session_name("phserved_seller_session");
session_start();

if (
    !isset($_SESSION["user_id"]) ||
    !isset($_SESSION["role"]) ||
    $_SESSION["role"] != "Admin"
) {
    header("Location: login.php");
    exit;
}

?>

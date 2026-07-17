<?php

session_set_cookie_params(0, dirname($_SERVER["SCRIPT_NAME"]));
session_name("phserved_seller_session");
session_start();

//Admin session
if (
    isset($_SESSION["user_id"]) &&
    isset($_SESSION["role"]) &&
    $_SESSION["role"] == "Admin"
) {
    header("Location: users.php");
    exit;
}

//Seller login
header("Location: login.php");
exit;

?>

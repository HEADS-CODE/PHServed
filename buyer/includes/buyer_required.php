<?php

//Buyer access

session_set_cookie_params(0, dirname($_SERVER["SCRIPT_NAME"]));
session_name("phserved_buyer_session");
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?required=1");
    exit;
}

?>

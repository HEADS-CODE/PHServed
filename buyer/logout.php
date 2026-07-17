<?php

session_set_cookie_params(0, dirname($_SERVER["SCRIPT_NAME"]));
session_name("phserved_buyer_session");
session_start();

//Clears session information
$_SESSION = array();

session_destroy();

//Buyer store
header("Location: index.php");
exit;

?>

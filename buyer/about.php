<?php

session_set_cookie_params(0, dirname($_SERVER["SCRIPT_NAME"]));
session_name("phserved_buyer_session");
session_start();

$page_title = "About Us";
$active_page = "about";

include("includes/header.php");
include("includes/sidebar.php");

?>

<div class="main-wrapper" id="mainWrapper">

    <?php include("includes/top_nav.php"); ?>

    <main class="page-content">

        <?php
        include("../includes/about_content.php");
        ?>

    </main>

    <?php include("../includes/footer.php"); ?>
<?php

require_once("includes/admin_required.php");

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
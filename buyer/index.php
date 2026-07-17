<?php

session_set_cookie_params(0, dirname($_SERVER["SCRIPT_NAME"]));
session_name("phserved_buyer_session");
session_start();

require_once("../config/db_connect.php");

//Store products
$product_sql =
    "SELECT
        products.product_id,
        products.product_name,
        products.product_image,
        products.price,
        products.stock_quantity,
        products.product_status,
        products.description,
        categories.category_name
     FROM products
     INNER JOIN categories
        ON products.category_id = categories.category_id
     ORDER BY
        categories.category_id,
        products.product_name";

$product_result = mysqli_query(
    $conn,
    $product_sql
);

$page_title = "Store";
$active_page = "store";

//Store return page
$return_category = "";

include("includes/header.php");
include("includes/sidebar.php");

?>

<div class="main-wrapper" id="mainWrapper">

    <?php include("includes/top_nav.php"); ?>

    <main class="page-content">

        <?php

        if (isset($_SESSION["cart_message"])) {
            $alert_message = $_SESSION["cart_message"];

            //Store message
            unset($_SESSION["cart_message"]);
            unset($_SESSION["cart_message_type"]);

            ?>

            <script>
                alert(
                    <?php echo json_encode($alert_message); ?>
                );
            </script>

            <?php
        }

        ?>

        <section class="store-hero">
            <h2>
                BUILD YOUR <span>DREAM PC</span>
            </h2>

            <p>
                Browse trusted computer parts used to enter,
                process, and display your data.<br>
                <span class="store-signin-callout">Sign in now</span>,
                once you're ready to order! It's that easy!
            </p>
        </section>

        <section>
            <h2 class="store-section-heading">
                Shop by Category
            </h2>

            <div class="category-link-grid">

                <a href="category.php?category=Input+Devices" class="category-link-card">
                    <img src="../images/icons/category_input_devices.png" alt="" class="category-card-icon">

                    <div class="category-card-text">
                        <h3>Input Devices</h3>

                        <p>
                            Keyboards, mice, controllers, scanners,
                            and similar computer input products.
                        </p>
                    </div>
                </a>

                <a href="category.php?category=Processing+Devices" class="category-link-card">
                    <img src="../images/icons/category_processing_devices.png" alt="" class="category-card-icon">

                    <div class="category-card-text">
                        <h3>Processing Devices</h3>

                        <p>
                            Processors, memory, power supplies,
                            graphics cards, and internal components.
                        </p>
                    </div>
                </a>

                <a href="category.php?category=Output+Devices" class="category-link-card">
                    <img src="../images/icons/category_output_devices.png" alt="" class="category-card-icon">

                    <div class="category-card-text">
                        <h3>Output Devices</h3>

                        <p>
                            Monitors, speakers, projectors, printers,
                            and similar computer output products.
                        </p>
                    </div>
                </a>

            </div>
        </section>

        <section>
            <h2 class="store-section-heading">
                Available Computer Parts
            </h2>

            <div class="store-product-grid">

                <?php if (
                    $product_result &&
                    mysqli_num_rows($product_result) > 0
                ): ?>

                    <?php while (
                        $product =
                        mysqli_fetch_assoc($product_result)
                    ): ?>

                        <?php
                        include("includes/product_card.php");
                        ?>

                    <?php endwhile; ?>

                <?php else: ?>

                    <div class="empty-products">
                        <h3>No products are available.</h3>

                        <p>
                            Products added by an administrator
                            will appear here.
                        </p>
                    </div>

                <?php endif; ?>

            </div>
        </section>
    </main>
    <?php include("../includes/footer.php"); ?>
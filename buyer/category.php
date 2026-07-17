<?php

session_set_cookie_params(0, dirname($_SERVER["SCRIPT_NAME"]));
session_name("phserved_buyer_session");
session_start();

require_once("../config/db_connect.php");

//Three categories
$allowed_categories = array(
    "Input Devices",
    "Processing Devices",
    "Output Devices"
);

$category_name = isset($_GET["category"])
    ? trim($_GET["category"])
    : "";

//Category check
if (!in_array($category_name, $allowed_categories)) {
    header("Location: index.php");
    exit;
}

$clean_category = mysqli_real_escape_string(
    $conn,
    $category_name
);

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
     WHERE categories.category_name = '$clean_category'
     ORDER BY products.product_name";

$product_result = mysqli_query(
    $conn,
    $product_sql
);

$page_title = $category_name;
$active_page = "category";

//Return category
$return_category = $category_name;

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

        <div class="category-page-header">

            <a href="index.php" class="category-back-button" aria-label="Return to Store">
                &larr;
            </a>

            <div>
                <h2>
                    <?php
                    echo htmlspecialchars($category_name);
                    ?>
                </h2>

                <p>
                    Products categorized as
                    <?php
                    echo htmlspecialchars($category_name);
                    ?>.
                </p>
            </div>

        </div>

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
                    <h3>No products found.</h3>

                    <p>
                        No products currently belong to this category.
                    </p>
                </div>

            <?php endif; ?>

        </div>

    </main>

    <?php include("../includes/footer.php"); ?>
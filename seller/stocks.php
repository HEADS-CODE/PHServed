<?php

require_once("includes/admin_required.php");
require_once("../config/db_connect.php");

$product_sql =
    "SELECT
        products.*,
        categories.category_name
     FROM products
     INNER JOIN categories
        ON products.category_id = categories.category_id
     ORDER BY products.product_name";

$product_result =
    mysqli_query($conn, $product_sql);

$page_title = "Stocks";
$active_page = "stocks";

include("includes/header.php");
include("includes/sidebar.php");

?>

<div class="main-wrapper" id="mainWrapper">

    <?php include("includes/top_nav.php"); ?>

    <main class="page-content">

        <?php

        if (isset($_SESSION["product_message"])) {
            $alert_message =
                $_SESSION["product_message"];

            unset($_SESSION["product_message"]);

            ?>

            <script>
                alert(
                    <?php echo json_encode($alert_message); ?>
                );
            </script>

            <?php
        }

        ?>

        <div class="stocks-heading">
            <div>
                <h2>Stocks</h2>

                <p>
                    Manage products, prices and quantities.
                </p>
            </div>

            <a href="product_form.php" class="phserved-button">
                Add Product
            </a>
        </div>

        <div class="stocks-table-wrap">

            <table class="stocks-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php while (
                        $product =
                        mysqli_fetch_assoc($product_result)
                    ): ?>

                        <?php

                        if ($product["product_image"] != "") {
                            $image_path =
                                "../images/products/" .
                                basename(
                                    $product["product_image"]
                                );
                        } else {
                            $image_path =
                                "../images/logo/phserved_logo.png";
                        }

                        $stock_quantity =
                            (int) $product["stock_quantity"];

                        if ($stock_quantity == 0) {
                            $stock_status = "Out of Stock";
                            $status_class = "status-out";
                        } elseif ($stock_quantity <= 5) {
                            $stock_status = "Low Stock";
                            $status_class = "status-low";
                        } else {
                            $stock_status = "Available";
                            $status_class = "status-available";
                        }

                        ?>

                        <tr>
                            <td>
                                <div class="stock-product">
                                    <img src="<?php
                                    echo htmlspecialchars(
                                        $image_path
                                    );
                                    ?>" alt="<?php
                                    echo htmlspecialchars(
                                        $product["product_name"]
                                    );
                                    ?>">

                                    <div>
                                        <strong>
                                            <?php
                                            echo htmlspecialchars(
                                                $product["product_name"]
                                            );
                                            ?>
                                        </strong>

                                        <small>
                                            <?php
                                            echo htmlspecialchars(
                                                $product["description"]
                                            );
                                            ?>
                                        </small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $product["category_name"]
                                );
                                ?>
                            </td>

                            <td>
                                ₱<?php
                                echo number_format(
                                    $product["price"],
                                    2
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo (int) $product[
                                    "stock_quantity"
                                ];
                                ?>
                            </td>

                            <td>
                                <span class="report-status <?php echo $status_class; ?>">
                                    <?php echo $stock_status; ?>
                                </span>
                            </td>

                            <td>
                                <div class="stock-actions">
                                    <a href="product_form.php?id=<?php
                                    echo (int) $product[
                                        "product_id"
                                    ];
                                    ?>" class="stock-edit-link">
                                        <img src="../images/icons/edit_product.png" alt="" class="action-icon"
                                            onerror="this.style.display='none'">
                                        Edit
                                    </a>

                                    <form method="POST" action="delete_product.php"
                                        onsubmit="return confirm('Delete this product?');">
                                        <input type="hidden" name="product_id"
                                            value="<?php echo (int) $product["product_id"]; ?>">

                                        <button type="submit" class="stock-delete-button">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                    <?php endwhile; ?>

                </tbody>
            </table>

        </div>

    </main>

    <?php include("../includes/footer.php"); ?>
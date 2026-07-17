<?php

require_once("includes/buyer_required.php");
require_once("../config/db_connect.php");

$user_id = (int) $_SESSION["user_id"];

//Buyer orders
$order_sql =
    "SELECT *
     FROM orders
     WHERE user_id = $user_id
     ORDER BY order_date DESC";

$order_result = mysqli_query($conn, $order_sql);

$buyer_order_number = $order_result
    ? mysqli_num_rows($order_result)
    : 0;

$page_title = "Orders";
$active_page = "orders";

include("includes/header.php");
include("includes/sidebar.php");

?>

<div class="main-wrapper" id="mainWrapper">

    <?php include("includes/top_nav.php"); ?>

    <main class="page-content">

        <div class="orders-heading">
            <div>
                <h2>Your Orders</h2>

                <p>
                    Completed purchases made using your account.
                </p>
            </div>

            <a href="index.php" class="phserved-button">
                Continue Shopping
            </a>
        </div>

        <?php if (
            $order_result &&
            mysqli_num_rows($order_result) > 0
        ): ?>

            <div class="orders-list">

                <?php while (
                    $order = mysqli_fetch_assoc($order_result)
                ): ?>

                    <?php

                    $order_id =
                        (int) $order["order_id"];

                    //Order products
                    $item_sql =
                        "SELECT *
                         FROM order_items
                         WHERE order_id = $order_id
                         ORDER BY order_item_id";

                    $item_result =
                        mysqli_query($conn, $item_sql);

                    ?>

                    <article class="order-card">

                        <div class="order-header">

                            <div>
                                <h3>
                                    Order #<?php
                                    echo $buyer_order_number;
                                    ?>
                                </h3>

                                <p>
                                    <?php
                                    echo date(
                                        "F j, Y - g:i A",
                                        strtotime(
                                            $order["order_date"]
                                        )
                                    );
                                    ?>
                                </p>
                            </div>

                            <span class="order-status">
                                <?php
                                echo htmlspecialchars(
                                    $order["order_status"]
                                );
                                ?>
                            </span>

                        </div>

                        <div class="order-products">

                            <?php while (
                                $item =
                                mysqli_fetch_assoc($item_result)
                            ): ?>

                                <div class="order-product-row">

                                    <div>
                                        <strong>
                                            <?php
                                            echo htmlspecialchars(
                                                $item["product_name"]
                                            );
                                            ?>
                                        </strong>

                                        <span>
                                            <?php
                                            echo (int) $item["quantity"];
                                            ?>
                                            ×
                                            ₱<?php
                                            echo number_format(
                                                $item["unit_price"],
                                                2
                                            );
                                            ?>
                                        </span>
                                    </div>

                                    <strong>
                                        ₱<?php
                                        echo number_format(
                                            $item["subtotal"],
                                            2
                                        );
                                        ?>
                                    </strong>

                                </div>

                            <?php endwhile; ?>

                        </div>

                        <div class="order-details">

                            <div>
                                <span>Payment Method</span>

                                <strong>
                                    <?php
                                    echo htmlspecialchars(
                                        $order["payment_method"]
                                    );
                                    ?>
                                </strong>
                            </div>

                            <div>
                                <span>Payment Status</span>

                                <strong>
                                    <?php
                                    echo htmlspecialchars(
                                        $order["payment_status"]
                                    );
                                    ?>
                                </strong>
                            </div>

                            <div>
                                <span>Contact Number</span>

                                <strong>
                                    <?php
                                    echo htmlspecialchars(
                                        $order["contact_number"]
                                    );
                                    ?>
                                </strong>
                            </div>

                        </div>

                        <div class="order-address">
                            <span>Delivery Address</span>

                            <p>
                                <?php
                                echo htmlspecialchars(
                                    $order["shipping_address"]
                                );
                                ?>
                            </p>
                        </div>

                        <div class="order-total">
                            <span>
                                Order Total
                                <small>
                                    (including ₱120.00 shipping)
                                </small>
                            </span>

                            <strong>
                                ₱<?php
                                echo number_format(
                                    $order["total_amount"],
                                    2
                                );
                                ?>
                            </strong>
                        </div>

                    </article>

                    <?php $buyer_order_number--; ?>

                <?php endwhile; ?>

            </div>

        <?php else: ?>

            <div class="empty-orders">
                <h2>No completed orders yet.</h2>

                <p>
                    Products you successfully purchase will
                    appear here.
                </p>

            </div>

        <?php endif; ?>

    </main>

    <?php include("../includes/footer.php"); ?>
<?php

require_once("includes/buyer_required.php");
require_once("../config/db_connect.php");

$user_id = (int) $_SESSION["user_id"];

if (!isset($_SESSION["last_order_id"])) {
    header("Location: orders.php");
    exit;
}

$order_id =
    (int) $_SESSION["last_order_id"];

$order_sql =
    "SELECT *
     FROM orders
     WHERE order_id = $order_id
     AND user_id = $user_id";

$order_result = mysqli_query(
    $conn,
    $order_sql
);

if (
    !$order_result ||
    mysqli_num_rows($order_result) != 1
) {
    header("Location: orders.php");
    exit;
}

$order = mysqli_fetch_assoc($order_result);

$number_sql =
    "SELECT COUNT(*) AS buyer_order_number
     FROM orders
     WHERE user_id = $user_id
     AND order_id <= $order_id";

$number_result = mysqli_query($conn, $number_sql);
$number_row = mysqli_fetch_assoc($number_result);
$buyer_order_number =
    (int) $number_row["buyer_order_number"];

$page_title = "Payment Successful";
$active_page = "";

include("includes/header.php");
include("includes/sidebar.php");

?>

<div class="main-wrapper" id="mainWrapper">

    <?php include("includes/top_nav.php"); ?>

    <main class="page-content">

        <section class="payment-success-card">

            <img src="../images/icons/payment_success.png" alt="Payment successful" class="success-icon">

            <h2>Payment Successful</h2>

            <p>
                Your simulated payment and PHServed order
                were completed successfully.
            </p>

            <div class="success-order-details">

                <div>
                    <span>Order Number</span>

                    <strong>
                        #<?php echo $buyer_order_number; ?>
                    </strong>
                </div>

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
                    <span>Order Status</span>

                    <strong>
                        <?php
                        echo htmlspecialchars(
                            $order["order_status"]
                        );
                        ?>
                    </strong>
                </div>

                <div>
                    <span>Total</span>

                    <strong>
                        ₱<?php
                        echo number_format(
                            $order["total_amount"],
                            2
                        );
                        ?>
                    </strong>
                </div>

            </div>

            <div class="success-actions">
                <a href="orders.php" class="phserved-button">
                    View Orders
                </a>

                <a href="index.php" class="phserved-button phserved-button-secondary">
                    Continue Shopping
                </a>
            </div>

        </section>

    </main>

    <?php include("../includes/footer.php"); ?>
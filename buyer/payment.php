<?php

require_once("includes/buyer_required.php");
require_once("../config/db_connect.php");

$user_id = (int) $_SESSION["user_id"];

//Checkout details
if (
    !isset($_SESSION["checkout_address"]) ||
    !isset($_SESSION["checkout_contact"])
) {
    header("Location: checkout.php");
    exit;
}

/* Payment cart */

$cart_sql =
    "SELECT
        cart_items.quantity,
        products.product_name,
        products.price,
        products.stock_quantity
     FROM cart_items
     INNER JOIN products
        ON cart_items.product_id = products.product_id
     WHERE cart_items.user_id = $user_id";

$cart_result = mysqli_query(
    $conn,
    $cart_sql
);

$payment_items = array();
$subtotal = 0;
$stock_problem = "";

if ($cart_result) {
    while (
        $item = mysqli_fetch_assoc($cart_result)
    ) {
        $quantity =
            (int) $item["quantity"];

        $stock =
            (int) $item["stock_quantity"];

        if ($stock <= 0) {
            $stock_problem =
                $item["product_name"] .
                " is now out of stock.";

            break;
        }

        if ($quantity > $stock) {
            $stock_problem =
                "Only " .
                $stock .
                " unit(s) of " .
                $item["product_name"] .
                " are currently available.";

            break;
        }

        $item["item_subtotal"] =
            (float) $item["price"] *
            $quantity;

        $subtotal +=
            $item["item_subtotal"];

        $payment_items[] = $item;
    }
}

if (
    count($payment_items) == 0 ||
    $stock_problem != ""
) {
    $_SESSION["cart_message"] =
        $stock_problem != ""
        ? $stock_problem
        : "Your cart is empty.";

    header("Location: cart.php");
    exit;
}

$shipping_fee = 120;

$order_total =
    $subtotal +
    $shipping_fee;

$page_title = "Payment";
$active_page = "";

include("includes/header.php");
include("includes/sidebar.php");

?>

<div class="main-wrapper" id="mainWrapper">
    <?php include("includes/top_nav.php"); ?>
    <main class="page-content">

        <?php if (
            isset($_SESSION["payment_message"])
        ): ?>

            <script>
                alert(
                    <?php
                    echo json_encode(
                        $_SESSION["payment_message"]
                    );
                    ?>
                );
            </script>

            <?php
            unset($_SESSION["payment_message"]);
            ?>

        <?php endif; ?>

        <div class="checkout-heading">
            <h2>Payment</h2>

            <p>
                Select your preferred payment method.
            </p>
        </div>

        <div class="payment-layout">

            <section class="payment-card">

                <h2>Select Payment Method</h2>

                <form method="POST" action="process_order.php">
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="Cash on Delivery" required>

                        <span>
                            <strong>Cash on Delivery</strong>
                        </span>
                    </label>

                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="GCash / E-Wallet" required>

                        <span>
                            <strong>GCash / E-Wallet</strong>
                        </span>
                    </label>

                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="Credit or Debit Card" required <span>
                        <strong>Credit or Debit Card</strong>
                        </span>
                    </label>

                    <div class="payment-actions">
                        <a href="checkout.php" class="phserved-button phserved-button-secondary">
                            Return to Checkout
                        </a>

                        <button type="submit" class="phserved-button" onclick="
                                return confirm(
                                    'Confirm this payment and place your order?'
                                );
                            ">
                            Confirm Payment
                        </button>
                    </div>
                </form>
            </section>
            <aside class="checkout-summary-card">
                <h2>Payment Summary</h2>

                <div class="checkout-item-list">

                    <?php foreach ($payment_items as $item): ?>

                        <div class="checkout-summary-item">
                            <div>
                                <strong>
                                    <?php
                                    echo htmlspecialchars(
                                        $item["product_name"]
                                    );
                                    ?>
                                </strong>

                                <span>
                                    Quantity:
                                    <?php
                                    echo (int) $item["quantity"];
                                    ?>
                                </span>
                            </div>

                            <strong>
                                ₱<?php
                                echo number_format(
                                    $item["item_subtotal"],
                                    2
                                );
                                ?>
                            </strong>
                        </div>

                <?php endforeach; ?>

                        </div>
                        <hr>
                        <div class="summary-row">
                            <span>Subtotal</span>

                            <strong>
                                ₱
                                <?php
                                echo number_format($subtotal, 2);
                                ?>
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Shipping Fee</span>

                    <strong>
                        ₱<?php
                        echo number_format(
                            $shipping_fee,
                            2
                        );
                        ?>
                    </strong>
                </div>

                <div class="summary-row summary-total">
                    <span>Total Payment</span>

                    <strong>
                        ₱<?php
                        echo number_format(
                            $order_total,
                            2
                        );
                        ?>
                    </strong>
                </div>

                <div class="delivery-summary">
                    <strong>Delivery Address</strong>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $_SESSION["checkout_address"]
                        );
                        ?>
                    </p>

                    <strong>Contact</strong>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $_SESSION["checkout_contact"]
                        );
                        ?>
                    </p>
                </div>
            </aside>
        </div>
    </main>
    <?php include("../includes/footer.php"); ?>
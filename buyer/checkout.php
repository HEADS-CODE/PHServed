<?php

require_once("includes/buyer_required.php");
require_once("../config/db_connect.php");

$user_id = (int) $_SESSION["user_id"];

$error_message = "";

/* Buyer information */

$user_sql =
    "SELECT *
     FROM users
     WHERE user_id = $user_id";

$user_result = mysqli_query(
    $conn,
    $user_sql
);

if (
    !$user_result ||
    mysqli_num_rows($user_result) != 1
) {
    header("Location: logout.php");
    exit;
}

$buyer = mysqli_fetch_assoc($user_result);

$default_address =
    $buyer["street"] .
    ", Barangay " .
    $buyer["barangay"] .
    ", " .
    $buyer["city"] .
    ", " .
    $buyer["province"] .
    " " .
    $buyer["zip_code"];

$default_contact =
    $buyer["contact_number"];

//Visible contact number
$contact_input = preg_replace(
    "/^\+63/",
    "",
    $default_contact
);

$shipping_address = $default_address;

/* Cart stock check */

$cart_sql =
    "SELECT
        cart_items.quantity,
        products.product_id,
        products.product_name,
        products.price,
        products.stock_quantity
     FROM cart_items
     INNER JOIN products
        ON cart_items.product_id = products.product_id
     WHERE cart_items.user_id = $user_id
     ORDER BY cart_items.date_added";

$cart_result = mysqli_query(
    $conn,
    $cart_sql
);

$checkout_items = array();
$subtotal = 0;
$stock_problem = "";

if ($cart_result) {
    while (
        $item = mysqli_fetch_assoc($cart_result)
    ) {
        $cart_quantity =
            (int) $item["quantity"];

        $current_stock =
            (int) $item["stock_quantity"];

        if ($current_stock <= 0) {
            $stock_problem =
                $item["product_name"] .
                " is now out of stock.";

            break;
        }

        if ($cart_quantity > $current_stock) {
            $stock_problem =
                "Only " .
                $current_stock .
                " unit(s) of " .
                $item["product_name"] .
                " are currently available.";

            break;
        }

        $item["item_subtotal"] =
            (float) $item["price"] *
            $cart_quantity;

        $subtotal +=
            $item["item_subtotal"];

        $checkout_items[] = $item;
    }
}

//Empty cart
if (count($checkout_items) == 0) {
    $_SESSION["cart_message"] =
        $stock_problem != ""
        ? $stock_problem
        : "Your cart is empty.";

    header("Location: cart.php");
    exit;
}

//Invalid cart
if ($stock_problem != "") {
    $_SESSION["cart_message"] =
        $stock_problem;

    header("Location: cart.php");
    exit;
}

$shipping_fee = 120;
$discount = 0;

$order_total =
    $subtotal +
    $shipping_fee -
    $discount;

/* Delivery information */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $shipping_address = trim(
        $_POST["shipping_address"] ?? ""
    );

    $contact_input = trim(
        $_POST["contact_number"] ?? ""
    );

    if ($shipping_address == "") {
        $error_message =
            "Please enter the complete delivery address.";
    } elseif (
        !preg_match(
            "/^9[0-9]{9}$/",
            $contact_input
        )
    ) {
        $error_message =
            "Enter exactly 10 mobile-number digits beginning with 9.";
    } else {
        $_SESSION["checkout_address"] =
            $shipping_address;

        $_SESSION["checkout_contact"] =
            "+63" . $contact_input;

        header("Location: payment.php");
        exit;
    }
}

$page_title = "Checkout";
$active_page = "";

include("includes/header.php");
include("includes/sidebar.php");

?>

<div class="main-wrapper" id="mainWrapper">

    <?php include("includes/top_nav.php"); ?>

    <main class="page-content">

        <div class="checkout-heading">
            <h2>Checkout</h2>

            <p>
                Confirm your delivery information before payment.
            </p>
        </div>

        <?php if ($error_message != ""): ?>

            <script>
                alert(
                    <?php echo json_encode($error_message); ?>
                );
            </script>

        <?php endif; ?>

        <div class="checkout-layout">

            <section class="checkout-form-card">

                <h2>Delivery Information</h2>

                <form method="POST" action="checkout.php">
                    <div class="mb-3">
                        <label class="form-label">
                            Complete Name
                        </label>

                        <input type="text" class="form-control" value="<?php
                        echo htmlspecialchars(
                            $buyer["complete_name"]
                        );
                        ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Email Address
                        </label>

                        <input type="email" class="form-control" value="<?php
                        echo htmlspecialchars(
                            $buyer["email"]
                        );
                        ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Complete Delivery Address
                        </label>

                        <textarea name="shipping_address" class="form-control" rows="4" required><?php
                        echo htmlspecialchars(
                            $shipping_address
                        );
                        ?></textarea>

                        <div class="form-text">
                            You may edit this address for the current order.
                            Your registered account address will not change.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            Philippine Mobile Number
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                +63
                            </span>

                            <input type="text" name="contact_number" class="form-control" value="<?php
                            echo htmlspecialchars(
                                $contact_input
                            );
                            ?>" placeholder="9171234567" pattern="9[0-9]{9}" minlength="10" maxlength="10"
                                inputmode="numeric" title="Enter exactly 10 digits beginning with 9." oninput="
                                    this.value =
                                    this.value
                                        .replace(/[^0-9]/g, '')
                                        .slice(0, 10)
                                " required>
                        </div>
                    </div>

                    <div class="checkout-form-buttons">
                        <a href="cart.php" class="phserved-button phserved-button-secondary">
                            Return to Cart
                        </a>

                        <button type="submit" class="phserved-button">
                            Continue to Payment
                        </button>
                    </div>

                </form>

            </section>

            <aside class="checkout-summary-card">

                <h2>Order Summary</h2>

                <div class="checkout-item-list">

                    <?php foreach ($checkout_items as $item): ?>

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
                        ₱<?php
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
                    <span>Total</span>

                    <strong>
                        ₱<?php
                        echo number_format(
                            $order_total,
                            2
                        );
                        ?>
                    </strong>
                </div>

            </aside>

        </div>

    </main>

    <?php include("../includes/footer.php"); ?>
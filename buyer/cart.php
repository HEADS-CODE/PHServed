<?php

require_once("includes/buyer_required.php");
require_once("../config/db_connect.php");

$user_id = (int) $_SESSION["user_id"];

/* Checkout stock */

if (
    $_SERVER["REQUEST_METHOD"] == "POST" &&
    isset($_POST["proceed_checkout"])
) {
    $check_sql =
        "SELECT
            cart_items.quantity,
            products.product_name,
            products.stock_quantity
         FROM cart_items
         INNER JOIN products
            ON cart_items.product_id = products.product_id
         WHERE cart_items.user_id = $user_id";

    $check_result = mysqli_query(
        $conn,
        $check_sql
    );

    if (
        !$check_result ||
        mysqli_num_rows($check_result) == 0
    ) {
        $_SESSION["cart_message"] =
            "Your cart is empty.";

        header("Location: cart.php");
        exit;
    }

    $stock_problem = "";

    while (
        $check_item =
        mysqli_fetch_assoc($check_result)
    ) {
        $cart_quantity =
            (int) $check_item["quantity"];

        $current_stock =
            (int) $check_item["stock_quantity"];

        if ($current_stock <= 0) {
            $stock_problem =
                $check_item["product_name"] .
                " is now out of stock.";

            break;
        }

        if ($cart_quantity > $current_stock) {
            $stock_problem =
                "Only " .
                $current_stock .
                " unit(s) of " .
                $check_item["product_name"] .
                " are currently available.";

            break;
        }
    }

    if ($stock_problem != "") {
        $_SESSION["cart_message"] =
            $stock_problem;

        header("Location: cart.php");
        exit;
    }

    //Checkout page
    header("Location: checkout.php");
    exit;
}

/* Buyer cart */

$cart_sql =
    "SELECT
        cart_items.cart_item_id,
        cart_items.quantity,
        products.product_id,
        products.product_name,
        products.product_image,
        products.price,
        products.stock_quantity,
        products.product_status,
        categories.category_name
     FROM cart_items
     INNER JOIN products
        ON cart_items.product_id = products.product_id
     INNER JOIN categories
        ON products.category_id = categories.category_id
     WHERE cart_items.user_id = $user_id
     ORDER BY cart_items.date_added DESC";

$cart_result = mysqli_query(
    $conn,
    $cart_sql
);

//Cart items
$cart_items = array();

$subtotal = 0;
$total_quantity = 0;
$has_stock_problem = false;

if ($cart_result) {
    while (
        $row = mysqli_fetch_assoc($cart_result)
    ) {
        $row["item_subtotal"] =
            (float) $row["price"] *
            (int) $row["quantity"];

        $subtotal +=
            $row["item_subtotal"];

        $total_quantity +=
            (int) $row["quantity"];

        if (
            (int) $row["stock_quantity"] <= 0 ||
            (int) $row["quantity"] >
            (int) $row["stock_quantity"]
        ) {
            $has_stock_problem = true;
        }

        $cart_items[] = $row;
    }
}

//Shipping fee
$shipping_fee = 0;

if (count($cart_items) > 0) {
    $shipping_fee = 120;
}

$discount = 0;

$order_total =
    $subtotal +
    $shipping_fee -
    $discount;

$page_title = "Shopping Cart";
$active_page = "";

include("includes/header.php");
include("includes/sidebar.php");

?>

<div class="main-wrapper" id="mainWrapper">

    <?php include("includes/top_nav.php"); ?>

    <main class="page-content">

        <?php

        //Cart alert
        if (isset($_SESSION["cart_message"])) {
            $alert_message =
                $_SESSION["cart_message"];

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

        <div class="cart-page-heading">
            <div>
                <h2>Your Shopping Cart</h2>

                <p>
                    Review your products before proceeding
                    to checkout.
                </p>
            </div>

            <a href="index.php" class="phserved-button phserved-button-secondary">
                Continue Shopping
            </a>
        </div>

        <?php if (count($cart_items) == 0): ?>

            <section class="empty-cart-card">
                <h2>Your cart is empty.</h2>

                <p>
                    Browse the PHServed Store and add the computer
                    parts you need.
                </p>

            </section>

        <?php else: ?>

            <div class="cart-layout">

                <!-- Cart items -->
                <section class="cart-items-section">

                    <?php foreach ($cart_items as $item): ?>

                        <?php

                        if (
                            isset($item["product_image"]) &&
                            $item["product_image"] != ""
                        ) {
                            $cart_image =
                                "../images/products/" .
                                basename(
                                    $item["product_image"]
                                );
                        } else {
                            $cart_image =
                                "../images/logo/phserved_logo.png";
                        }

                        $item_quantity =
                            (int) $item["quantity"];

                        $current_stock =
                            (int) $item["stock_quantity"];

                        $item_has_problem = false;

                        if (
                            $current_stock <= 0 ||
                            $item_quantity > $current_stock
                        ) {
                            $item_has_problem = true;
                        }

                        ?>

                        <article class="cart-item-card">

                            <div class="cart-item-image-area">
                                <img src="<?php
                                echo htmlspecialchars(
                                    $cart_image
                                );
                                ?>" alt="<?php
                                echo htmlspecialchars(
                                    $item["product_name"]
                                );
                                ?>" class="cart-item-image">
                            </div>

                            <div class="cart-item-information">

                                <span class="cart-item-category">
                                    <?php
                                    echo htmlspecialchars(
                                        $item["category_name"]
                                    );
                                    ?>
                                </span>

                                <h3>
                                    <?php
                                    echo htmlspecialchars(
                                        $item["product_name"]
                                    );
                                    ?>
                                </h3>

                                <p class="cart-unit-price">
                                    Unit price:
                                    <strong>
                                        ₱<?php
                                        echo number_format(
                                            $item["price"],
                                            2
                                        );
                                        ?>
                                    </strong>
                                </p>

                                <p class="cart-current-stock">
                                    Current stock:
                                    <strong>
                                        <?php
                                        echo $current_stock;
                                        ?>
                                    </strong>
                                </p>

                                <?php if ($item_has_problem): ?>

                                    <p class="cart-stock-warning">
                                        The selected quantity is no longer
                                        available. Update it using an available
                                        quantity or remove the product.
                                    </p>

                                <?php endif; ?>

                                <div class="cart-item-actions">
                                    <form method="POST" action="update_cart.php" class="cart-update-form">
                                        <input type="hidden" name="cart_item_id" value="<?php
                                        echo (int) $item["cart_item_id"];
                                        ?>">

                                        <label>
                                            Quantity

                                            <input type="number" name="quantity" value="<?php echo $item_quantity; ?>"
                                                data-saved-quantity="<?php
                                                echo $item_quantity;
                                                ?>" min="1" max="<?php
                                                echo max(1, $current_stock);
                                                ?>" required <?php
                                                echo $current_stock <= 0
                                                    ? "disabled"
                                                    : "";
                                                ?>>
                                        </label>

                                        <button type="submit" class="cart-update-button" disabled>
                                            Update
                                        </button>
                                    </form>

                                    <form method="POST" action="remove_from_cart.php" class="cart-remove-form">
                                        <input type="hidden" name="cart_item_id" value="<?php
                                        echo (int) $item["cart_item_id"];
                                        ?>">

                                        <button type="submit" class="cart-remove-button">
                                            Remove from Cart
                                        </button>
                                    </form>
                                </div>

                            </div>

                            <div class="cart-item-subtotal">
                                <span>Item subtotal</span>

                                <strong>
                                    ₱<?php
                                    echo number_format(
                                        $item["item_subtotal"],
                                        2
                                    );
                                    ?>
                                </strong>
                            </div>

                        </article>

                    <?php endforeach; ?>

                </section>

                <!-- Order summary -->
                <aside class="cart-summary-card">

                    <h2>Order Summary</h2>

                    <div class="summary-row">
                        <span>
                            Items
                            (<?php echo $total_quantity; ?>)
                        </span>

                        <strong>
                            ₱<?php
                            echo number_format(
                                $subtotal,
                                2
                            );
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

                    <div class="summary-row">
                        <span>Discount</span>

                        <strong>
                            -₱<?php
                            echo number_format(
                                $discount,
                                2
                            );
                            ?>
                        </strong>
                    </div>

                    <hr>

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

                    <?php if ($has_stock_problem): ?>

                        <p class="summary-warning">
                            Resolve the unavailable product quantities
                            before proceeding to checkout.
                        </p>

                    <?php endif; ?>

                    <form method="POST" action="cart.php" class="cart-checkout-form">
                        <button type="submit" name="proceed_checkout" class="phserved-button checkout-button" <?php
                        echo $has_stock_problem
                            ? "disabled"
                            : "";
                        ?>>
                            Proceed to Checkout
                        </button>
                    </form>

                    <a href="index.php" class="continue-shopping-link">
                        Continue Shopping
                    </a>

                </aside>

            </div>

        <?php endif; ?>

    </main>

    <script>
        var quantityInputs = document.querySelectorAll(
            ".cart-update-form input[name='quantity']"
        );

        var allowCartLeave = false;

        function hasUnsavedQuantity() {
            var hasChanges = false;

            quantityInputs.forEach(function (input) {
                if (
                    input.value !==
                    input.dataset.savedQuantity
                ) {
                    hasChanges = true;
                }
            });

            return hasChanges;
        }

        function confirmDiscardChanges() {
            return confirm(
                "You have not saved your quantity changes. " +
                "Select OK to discard them and leave this page, " +
                "or Cancel to stay and press Update."
            );
        }

        quantityInputs.forEach(function (input) {
            var form = input.closest(".cart-update-form");
            var button = form.querySelector(
                ".cart-update-button"
            );

            input.addEventListener("input", function () {
                button.disabled =
                    !input.checkValidity() ||
                    input.value ===
                    input.dataset.savedQuantity;
            });

            form.addEventListener("submit", function () {
                allowCartLeave = true;
            });
        });

        document.querySelectorAll("a[href]").forEach(
            function (link) {
                link.addEventListener("click", function (event) {
                    if (
                        hasUnsavedQuantity() &&
                        !confirmDiscardChanges()
                    ) {
                        event.preventDefault();
                        return;
                    }

                    allowCartLeave = true;
                });
            }
        );

        document.querySelectorAll(".cart-remove-form").forEach(
            function (form) {
                form.addEventListener("submit", function (event) {
                    var message =
                        "Remove this product from your cart?";

                    if (hasUnsavedQuantity()) {
                        message =
                            "You have not saved your quantity changes. " +
                            "Removing this product will discard them. " +
                            "Select OK to continue or Cancel to stay.";
                    }

                    if (!confirm(message)) {
                        event.preventDefault();
                        return;
                    }

                    allowCartLeave = true;
                });
            }
        );

        var checkoutForm = document.querySelector(
            ".cart-checkout-form"
        );

        if (checkoutForm) {
            checkoutForm.addEventListener(
                "submit",
                function (event) {
                    if (
                        hasUnsavedQuantity() &&
                        !confirmDiscardChanges()
                    ) {
                        event.preventDefault();
                        return;
                    }

                    allowCartLeave = true;
                }
            );
        }

        window.addEventListener("beforeunload", function (event) {
            if (
                hasUnsavedQuantity() &&
                !allowCartLeave
            ) {
                event.preventDefault();
                event.returnValue = "";
            }
        });
    </script>

    <?php include("../includes/footer.php"); ?>
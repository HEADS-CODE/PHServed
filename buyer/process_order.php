<?php

require_once("includes/buyer_required.php");
require_once("../config/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: payment.php");
    exit;
}

if (
    !isset($_SESSION["checkout_address"]) ||
    !isset($_SESSION["checkout_contact"])
) {
    header("Location: checkout.php");
    exit;
}

$user_id = (int) $_SESSION["user_id"];

$allowed_methods = array(
    "Cash on Delivery",
    "GCash / E-Wallet",
    "Credit or Debit Card"
);

$payment_method = isset($_POST["payment_method"])
    ? trim($_POST["payment_method"])
    : "";

if (!in_array($payment_method, $allowed_methods)) {
    $_SESSION["payment_message"] =
        "Please select a valid payment method.";

    header("Location: payment.php");
    exit;
}

/* Final stock check */

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
     WHERE cart_items.user_id = $user_id";

$cart_result = mysqli_query(
    $conn,
    $cart_sql
);

$order_products = array();
$subtotal = 0;
$stock_problem = "";

if ($cart_result) {
    while (
        $product = mysqli_fetch_assoc($cart_result)
    ) {
        $quantity =
            (int) $product["quantity"];

        $stock =
            (int) $product["stock_quantity"];

        if ($stock <= 0) {
            $stock_problem =
                $product["product_name"] .
                " is now out of stock.";

            break;
        }

        if ($quantity > $stock) {
            $stock_problem =
                "Only " .
                $stock .
                " unit(s) of " .
                $product["product_name"] .
                " are currently available.";

            break;
        }

        $product["item_subtotal"] =
            (float) $product["price"] *
            $quantity;

        $subtotal +=
            $product["item_subtotal"];

        $order_products[] = $product;
    }
}

if (count($order_products) == 0) {
    $_SESSION["cart_message"] =
        "Your cart is empty.";

    header("Location: cart.php");
    exit;
}

if ($stock_problem != "") {
    $_SESSION["cart_message"] =
        $stock_problem;

    header("Location: cart.php");
    exit;
}

$shipping_fee = 120;

$order_total =
    $subtotal +
    $shipping_fee;

$shipping_address =
    mysqli_real_escape_string(
        $conn,
        $_SESSION["checkout_address"]
    );

$contact_number =
    mysqli_real_escape_string(
        $conn,
        $_SESSION["checkout_contact"]
    );

$payment_method =
    mysqli_real_escape_string(
        $conn,
        $payment_method
    );

/* New order */

$order_sql =
    "INSERT INTO orders (
        user_id,
        shipping_address,
        contact_number,
        payment_method,
        payment_status,
        order_status,
        total_amount
    ) VALUES (
        $user_id,
        '$shipping_address',
        '$contact_number',
        '$payment_method',
        'Paid',
        'Completed',
        $order_total
    )";

if (!mysqli_query($conn, $order_sql)) {
    $_SESSION["payment_message"] =
        "The order could not be created.";

    header("Location: payment.php");
    exit;
}

$order_id =
    mysqli_insert_id($conn);

/* Order products */

foreach ($order_products as $product) {
    $product_id =
        (int) $product["product_id"];

    $product_name =
        mysqli_real_escape_string(
            $conn,
            $product["product_name"]
        );

    $quantity =
        (int) $product["quantity"];

    $unit_price =
        (float) $product["price"];

    $item_subtotal =
        (float) $product["item_subtotal"];

    $order_item_sql =
        "INSERT INTO order_items (
            order_id,
            product_id,
            product_name,
            quantity,
            unit_price,
            subtotal
        ) VALUES (
            $order_id,
            $product_id,
            '$product_name',
            $quantity,
            $unit_price,
            $item_subtotal
        )";

    mysqli_query(
        $conn,
        $order_item_sql
    );

    $new_stock =
        (int) $product["stock_quantity"] -
        $quantity;

    //Stock status
    if ($new_stock == 0) {
        $new_status =
            "Out of Stock";
    } elseif ($new_stock <= 5) {
        $new_status =
            "Low Stock";
    } else {
        $new_status =
            "Available";
    }

    $stock_sql =
        "UPDATE products
         SET
            stock_quantity = $new_stock,
            product_status = '$new_status',
            date_updated = CURRENT_TIMESTAMP
         WHERE product_id = $product_id";

    mysqli_query(
        $conn,
        $stock_sql
    );
}

/* Clear cart */

$clear_cart_sql =
    "DELETE FROM cart_items
     WHERE user_id = $user_id";

mysqli_query(
    $conn,
    $clear_cart_sql
);

//Completed order
$_SESSION["last_order_id"] =
    $order_id;

//Checkout session
unset($_SESSION["checkout_address"]);
unset($_SESSION["checkout_contact"]);

header("Location: payment_success.php");
exit;

?>
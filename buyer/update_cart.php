<?php

require_once("includes/buyer_required.php");
require_once("../config/db_connect.php");

//Cart update
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: cart.php");
    exit;
}

$user_id = (int) $_SESSION["user_id"];

$cart_item_id = isset($_POST["cart_item_id"])
    ? (int) $_POST["cart_item_id"]
    : 0;

$new_quantity = isset($_POST["quantity"])
    ? (int) $_POST["quantity"]
    : 0;

$cart_sql =
    "SELECT
        cart_items.cart_item_id,
        cart_items.quantity,
        products.product_name,
        products.stock_quantity
     FROM cart_items
     INNER JOIN products
        ON cart_items.product_id = products.product_id
     WHERE cart_items.cart_item_id = $cart_item_id
     AND cart_items.user_id = $user_id";

$cart_result = mysqli_query(
    $conn,
    $cart_sql
);

if (
    !$cart_result ||
    mysqli_num_rows($cart_result) != 1
) {
    $_SESSION["cart_message"] =
        "The selected cart item could not be found.";

    header("Location: cart.php");
    exit;
}

$cart_item = mysqli_fetch_assoc($cart_result);

$product_name =
    $cart_item["product_name"];

$stock_quantity =
    (int) $cart_item["stock_quantity"];

if ($new_quantity < 1) {
    $_SESSION["cart_message"] =
        "Quantity must be at least 1.";

    header("Location: cart.php");
    exit;
}

if ($stock_quantity <= 0) {
    $_SESSION["cart_message"] =
        $product_name .
        " is now out of stock. Please remove it from your cart.";

    header("Location: cart.php");
    exit;
}

if ($new_quantity > $stock_quantity) {
    $_SESSION["cart_message"] =
        "Only " .
        $stock_quantity .
        " unit(s) of " .
        $product_name .
        " are currently available.";

    header("Location: cart.php");
    exit;
}

$update_sql =
    "UPDATE cart_items
     SET quantity = $new_quantity
     WHERE cart_item_id = $cart_item_id
     AND user_id = $user_id";

if (mysqli_query($conn, $update_sql)) {
    $_SESSION["cart_message"] =
        $product_name .
        " quantity was updated.";
} else {
    $_SESSION["cart_message"] =
        "The cart quantity could not be updated.";
}

header("Location: cart.php");
exit;

?>
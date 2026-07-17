<?php

require_once("includes/buyer_required.php");
require_once("../config/db_connect.php");

//Remove form
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: cart.php");
    exit;
}

$user_id = (int) $_SESSION["user_id"];

$cart_item_id = isset($_POST["cart_item_id"])
    ? (int) $_POST["cart_item_id"]
    : 0;

//Cart item owner
$find_sql =
    "SELECT
        cart_items.cart_item_id,
        products.product_name
     FROM cart_items
     INNER JOIN products
        ON cart_items.product_id = products.product_id
     WHERE cart_items.cart_item_id = $cart_item_id
     AND cart_items.user_id = $user_id";

$find_result = mysqli_query(
    $conn,
    $find_sql
);

if (
    !$find_result ||
    mysqli_num_rows($find_result) != 1
) {
    $_SESSION["cart_message"] =
        "The selected cart item could not be found.";

    header("Location: cart.php");
    exit;
}

$cart_item = mysqli_fetch_assoc($find_result);

$product_name =
    $cart_item["product_name"];

$delete_sql =
    "DELETE FROM cart_items
     WHERE cart_item_id = $cart_item_id
     AND user_id = $user_id";

if (mysqli_query($conn, $delete_sql)) {
    $_SESSION["cart_message"] =
        $product_name .
        " was removed from your cart.";
} else {
    $_SESSION["cart_message"] =
        "The product could not be removed.";
}

header("Location: cart.php");
exit;

?>
<?php

session_set_cookie_params(0, dirname($_SERVER["SCRIPT_NAME"]));
session_name("phserved_buyer_session");
session_start();

require_once("../config/db_connect.php");

$allowed_categories = array(
    "Input Devices",
    "Processing Devices",
    "Output Devices"
);

//Return page
$return_category = isset($_POST["return_category"])
    ? trim($_POST["return_category"])
    : "";

if (in_array($return_category, $allowed_categories)) {
    $return_page =
        "category.php?category=" .
        urlencode($return_category);
} else {
    $return_page = "index.php";
}

//Submitted cart form
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit;
}

//Buyer sign in
if (!isset($_SESSION["user_id"])) {
    $_SESSION["login_notice"] =
        "Please sign in before adding a product to your cart.";

    $_SESSION["return_after_login"] =
        $return_page;

    header("Location: login.php");
    exit;
}

$user_id = (int) $_SESSION["user_id"];

$product_id = isset($_POST["product_id"])
    ? (int) $_POST["product_id"]
    : 0;

$quantity = isset($_POST["quantity"])
    ? (int) $_POST["quantity"]
    : 1;

if ($quantity < 1) {
    $quantity = 1;
}

//Product check
$product_sql =
    "SELECT
        product_id,
        product_name,
        stock_quantity
     FROM products
     WHERE product_id = $product_id";

$product_result = mysqli_query(
    $conn,
    $product_sql
);

if (
    !$product_result ||
    mysqli_num_rows($product_result) != 1
) {
    $_SESSION["cart_message"] =
        "The selected product could not be found.";

    $_SESSION["cart_message_type"] =
        "danger";

    header("Location: " . $return_page);
    exit;
}

$product = mysqli_fetch_assoc($product_result);

$stock_quantity =
    (int) $product["stock_quantity"];

$product_name =
    $product["product_name"];

//Product availability
if ($stock_quantity <= 0) {
    $_SESSION["cart_message"] =
        $product_name . " is currently out of stock.";

    $_SESSION["cart_message_type"] =
        "warning";

    header("Location: " . $return_page);
    exit;
}

//Stock limit
if ($quantity > $stock_quantity) {
    $_SESSION["cart_message"] =
        "Only " .
        $stock_quantity .
        " unit(s) of " .
        $product_name .
        " are available.";

    $_SESSION["cart_message_type"] =
        "warning";

    header("Location: " . $return_page);
    exit;
}

//Existing cart item
$cart_sql =
    "SELECT
        cart_item_id,
        quantity
     FROM cart_items
     WHERE user_id = $user_id
     AND product_id = $product_id";

$cart_result = mysqli_query(
    $conn,
    $cart_sql
);

if (mysqli_num_rows($cart_result) == 1) {
    $cart_item = mysqli_fetch_assoc($cart_result);

    $new_quantity =
        (int) $cart_item["quantity"] + $quantity;

    if ($new_quantity > $stock_quantity) {
        $_SESSION["cart_message"] =
            "Your cart cannot exceed the " .
            $stock_quantity .
            " available unit(s) of " .
            $product_name . ".";

        $_SESSION["cart_message_type"] =
            "warning";

        header("Location: " . $return_page);
        exit;
    }

    $cart_item_id =
        (int) $cart_item["cart_item_id"];

    $save_cart_sql =
        "UPDATE cart_items
         SET quantity = $new_quantity
         WHERE cart_item_id = $cart_item_id
         AND user_id = $user_id";
} else {
    $save_cart_sql =
        "INSERT INTO cart_items (
            user_id,
            product_id,
            quantity
        ) VALUES (
            $user_id,
            $product_id,
            $quantity
        )";

}

if (!mysqli_query($conn, $save_cart_sql)) {
    $_SESSION["cart_message"] =
        "The product quantity could not be saved to your cart.";

    $_SESSION["cart_message_type"] =
        "danger";

    header("Location: " . $return_page);
    exit;
}

$_SESSION["cart_message"] =
    $product_name .
    " was added to your cart.";

$_SESSION["cart_message_type"] =
    "success";

header("Location: " . $return_page);
exit;

?>
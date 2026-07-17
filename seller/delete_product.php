<?php

require_once("includes/admin_required.php");
require_once("../config/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: stocks.php");
    exit;
}

$admin_id = (int) $_SESSION["user_id"];
$product_id = isset($_POST["product_id"])
    ? (int) $_POST["product_id"]
    : 0;

$product_result = mysqli_query(
    $conn,
    "SELECT product_name, product_image
     FROM products
     WHERE product_id = $product_id"
);

if (!$product_result || mysqli_num_rows($product_result) != 1) {
    $_SESSION["product_message"] =
        "The selected product could not be found.";

    header("Location: stocks.php");
    exit;
}

$product = mysqli_fetch_assoc($product_result);

$order_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS order_count
     FROM order_items
     WHERE product_id = $product_id"
);

$order_row = mysqli_fetch_assoc($order_result);

if ((int) $order_row["order_count"] > 0) {
    $_SESSION["product_message"] =
        "This product cannot be deleted because it belongs to completed " .
        "order history. Set its stock to 0 instead.";

    header("Location: stocks.php");
    exit;
}

mysqli_begin_transaction($conn);

try {
    if (
        !mysqli_query(
            $conn,
            "DELETE FROM cart_items
         WHERE product_id = $product_id"
        )
    ) {
        throw new Exception("Cart cleanup failed.");
    }

    if (
        !mysqli_query(
            $conn,
            "DELETE FROM products
         WHERE product_id = $product_id"
        )
    ) {
        throw new Exception("Product deletion failed.");
    }

    $action = mysqli_real_escape_string(
        $conn,
        "Deleted product: " . $product["product_name"]
    );

    if (
        !mysqli_query(
            $conn,
            "INSERT INTO audit_logs (user_id, action)
         VALUES ($admin_id, '$action')"
        )
    ) {
        throw new Exception("Audit logging failed.");
    }

    mysqli_commit($conn);

    $product_image = basename($product["product_image"]);

    if (
        $product_image != "" &&
        preg_match("/^[0-9]+_/", $product_image)
    ) {
        $image_path =
            __DIR__ .
            "/../images/products/" .
            $product_image;

        if (is_file($image_path)) {
            unlink($image_path);
        }
    }

    $_SESSION["product_message"] =
        $product["product_name"] . " was deleted.";
} catch (Exception $exception) {
    mysqli_rollback($conn);

    $_SESSION["product_message"] =
        "The product could not be deleted.";
}

header("Location: stocks.php");
exit;

?>
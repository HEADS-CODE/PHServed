<?php

require_once("includes/admin_required.php");
require_once("../config/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: users.php");
    exit;
}

$selected_ids = isset($_POST["user_ids"])
    ? $_POST["user_ids"]
    : array();

$buyer_ids = array();

foreach ($selected_ids as $selected_id) {
    $selected_id = (int) $selected_id;

    if ($selected_id > 0) {
        $buyer_ids[] = $selected_id;
    }
}

$buyer_ids = array_values(array_unique($buyer_ids));

if (count($buyer_ids) == 0) {
    $_SESSION["user_message"] =
        "Please select at least one Buyer account.";

    header("Location: users.php");
    exit;
}

$id_list = implode(",", $buyer_ids);

$valid_result = mysqli_query(
    $conn,
    "SELECT user_id
     FROM users
     WHERE role = 'Buyer'
     AND user_id IN ($id_list)"
);

if (!$valid_result) {
    $_SESSION["user_message"] =
        "The selected user accounts could not be checked.";

    header("Location: users.php");
    exit;
}

$valid_ids = array();

while ($valid_user = mysqli_fetch_assoc($valid_result)) {
    $valid_ids[] = (int) $valid_user["user_id"];
}

if (count($valid_ids) == 0) {
    $_SESSION["user_message"] =
        "No valid Buyer accounts were selected.";

    header("Location: users.php");
    exit;
}

$valid_id_list = implode(",", $valid_ids);
$admin_id = (int) $_SESSION["user_id"];

mysqli_begin_transaction($conn);

try {
    $order_result = mysqli_query(
        $conn,
        "SELECT order_id
         FROM orders
         WHERE user_id IN ($valid_id_list)"
    );

    if (!$order_result) {
        throw new Exception("Could not find Buyer orders.");
    }

    $order_ids = array();

    while ($order = mysqli_fetch_assoc($order_result)) {
        $order_ids[] = (int) $order["order_id"];
    }

    if (count($order_ids) > 0) {
        $order_id_list = implode(",", $order_ids);

        if (
            !mysqli_query(
                $conn,
                "DELETE FROM order_items
             WHERE order_id IN ($order_id_list)"
            )
        ) {
            throw new Exception("Could not delete order items.");
        }
    }

    if (
        !mysqli_query(
            $conn,
            "DELETE FROM orders
         WHERE user_id IN ($valid_id_list)"
        )
    ) {
        throw new Exception("Could not delete orders.");
    }

    if (
        !mysqli_query(
            $conn,
            "DELETE FROM cart_items
         WHERE user_id IN ($valid_id_list)"
        )
    ) {
        throw new Exception("Could not delete cart items.");
    }

    if (
        !mysqli_query(
            $conn,
            "DELETE FROM users
         WHERE role = 'Buyer'
         AND user_id IN ($valid_id_list)"
        )
    ) {
        throw new Exception("Could not delete Buyer accounts.");
    }

    $deleted_count = mysqli_affected_rows($conn);

    $action = mysqli_real_escape_string(
        $conn,
        "Deleted " . $deleted_count . " Buyer account(s)"
    );

    if (
        !mysqli_query(
            $conn,
            "INSERT INTO audit_logs (user_id, action)
         VALUES ($admin_id, '$action')"
        )
    ) {
        throw new Exception("Could not record the action.");
    }

    mysqli_commit($conn);

    $_SESSION["user_message"] =
        $deleted_count == 1
        ? "The user has been successfully deleted."
        : "The selected users have been successfully deleted.";
} catch (Exception $exception) {
    mysqli_rollback($conn);

    $_SESSION["user_message"] =
        "The selected user accounts could not be deleted.";
}

header("Location: users.php");
exit;

?>
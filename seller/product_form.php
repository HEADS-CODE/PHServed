<?php

require_once("includes/admin_required.php");
require_once("../config/db_connect.php");

$admin_id = (int)$_SESSION["user_id"];

$product_id = isset($_GET["id"])
    ? (int)$_GET["id"]
    : 0;

$is_edit = $product_id > 0;

$product_name = "";
$category_id = 0;
$price = "";
$stock_quantity = "";
$description = "";
$product_image = "";
$error_message = "";

/* Product details */

if ($is_edit) {
    $find_sql =
        "SELECT *
         FROM products
         WHERE product_id = $product_id";

    $find_result =
        mysqli_query($conn, $find_sql);

    if (mysqli_num_rows($find_result) != 1) {
        header("Location: stocks.php");
        exit;
    }

    $current_product =
        mysqli_fetch_assoc($find_result);

    $product_name =
        $current_product["product_name"];

    $category_id =
        (int)$current_product["category_id"];

    $price =
        $current_product["price"];

    $stock_quantity =
        $current_product["stock_quantity"];

    $description =
        $current_product["description"];

    $product_image =
        $current_product["product_image"];
}

/* Product form */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name =
        trim($_POST["product_name"] ?? "");

    $category_id =
        (int)($_POST["category_id"] ?? 0);

    $price =
        (float)($_POST["price"] ?? 0);

    $stock_quantity =
        (int)($_POST["stock_quantity"] ?? 0);

    $description =
        trim($_POST["description"] ?? "");

    if (
        $product_name == "" ||
        $category_id == 0 ||
        $price <= 0 ||
        $stock_quantity < 0 ||
        $description == ""
    ) {
        $error_message =
            "Please enter valid product information.";
    }

/* Product image */

    if (
        $error_message == "" &&
        isset($_FILES["product_image"]) &&
        $_FILES["product_image"]["error"] == 0
    ) {
        $allowed_types = array(
            "image/jpeg",
            "image/png"
        );

        $file_type =
            $_FILES["product_image"]["type"];

        $file_size =
            $_FILES["product_image"]["size"];

        if (!in_array($file_type, $allowed_types)) {
            $error_message =
                "Only JPG and PNG images are allowed.";
        } elseif ($file_size > 2097152) {
            $error_message =
                "The image must not exceed 2MB.";
        } else {
            $original_name =
                basename(
                    $_FILES["product_image"]["name"]
                );

            $new_name =
                time() .
                "_" .
                str_replace(
                    " ",
                    "_",
                    $original_name
                );

            $upload_path =
                __DIR__ .
                "/../images/products/" .
                $new_name;

            if (
                move_uploaded_file(
                    $_FILES["product_image"]["tmp_name"],
                    $upload_path
                )
            ) {
                $product_image =
                    $new_name;
            } else {
                $error_message =
                    "The image could not be uploaded.";
            }
        }
    }

//Required image
    if (
        !$is_edit &&
        $product_image == "" &&
        $error_message == ""
    ) {
        $error_message =
            "Please select a product image.";
    }

    if ($error_message == "") {
//Stock status
        if ($stock_quantity == 0) {
            $product_status =
                "Out of Stock";
        } elseif ($stock_quantity <= 5) {
            $product_status =
                "Low Stock";
        } else {
            $product_status =
                "Available";
        }

        $db_name =
            mysqli_real_escape_string(
                $conn,
                $product_name
            );

        $db_description =
            mysqli_real_escape_string(
                $conn,
                $description
            );

        $db_image =
            mysqli_real_escape_string(
                $conn,
                $product_image
            );

        $product_saved = false;

        if ($is_edit) {
            $update_sql =
                "UPDATE products
                 SET
                    product_name = '$db_name',
                    category_id = $category_id,
                    product_image = '$db_image',
                    price = $price,
                    stock_quantity = $stock_quantity,
                    product_status = '$product_status',
                    description = '$db_description',
                    date_updated = CURRENT_TIMESTAMP
                 WHERE product_id = $product_id";

            $product_saved =
                mysqli_query($conn, $update_sql);

            $action =
                mysqli_real_escape_string(
                    $conn,
                    "Updated product: " .
                    $product_name
                );

        } else {
            $insert_sql =
                "INSERT INTO products (
                    category_id,
                    product_name,
                    product_image,
                    price,
                    stock_quantity,
                    product_status,
                    description
                ) VALUES (
                    $category_id,
                    '$db_name',
                    '$db_image',
                    $price,
                    $stock_quantity,
                    '$product_status',
                    '$db_description'
                )";

            $product_saved =
                mysqli_query($conn, $insert_sql);

            $action =
                mysqli_real_escape_string(
                    $conn,
                    "Added product: " .
                    $product_name
                );

        }

        if ($product_saved) {
            $_SESSION["product_message"] =
                $product_name .
                ($is_edit
                    ? " was updated."
                    : " was added.");

//Audit record
            mysqli_query(
                $conn,
                "INSERT INTO audit_logs (
                    user_id,
                    action
                ) VALUES (
                    $admin_id,
                    '$action'
                )"
            );

            header("Location: stocks.php");
            exit;
        }

        $error_message =
            "The product could not be saved.";
    }
}

/* Product categories */

$category_result =
    mysqli_query(
        $conn,
        "SELECT *
         FROM categories
         ORDER BY category_id"
    );

$page_title =
    $is_edit ? "Edit Product" : "Add Product";

$active_page = "stocks";

include("includes/header.php");
include("includes/sidebar.php");

?>

<div class="main-wrapper" id="mainWrapper">

    <?php include("includes/top_nav.php"); ?>

    <main class="page-content">

        <div class="product-form-card">

            <h2>
                <?php echo htmlspecialchars($page_title); ?>
            </h2>

            <?php if ($error_message != ""): ?>

                <script>
                    alert(
                        <?php echo json_encode($error_message); ?>
                    );
                </script>

            <?php endif; ?>

            <?php if (
                $is_edit &&
                $product_image != ""
            ): ?>

                <img
                    src="../images/products/<?php
                        echo htmlspecialchars(
                            basename($product_image)
                        );
                    ?>"
                    alt="Current product image"
                    class="current-product-image"
                >

            <?php endif; ?>

            <form
                method="POST"
                enctype="multipart/form-data"
                action="product_form.php<?php
                    echo $is_edit
                        ? "?id=" . $product_id
                        : "";
                ?>"
            >

                <div class="mb-3">
                    <label class="form-label">
                        Product Name
                    </label>

                    <input
                        type="text"
                        name="product_name"
                        class="form-control"
                        value="<?php
                            echo htmlspecialchars(
                                $product_name
                            );
                        ?>"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Product Category
                    </label>

                    <select
                        name="category_id"
                        class="form-select"
                        required
                    >
                        <option value="">
                            Select Category
                        </option>

                        <?php while (
                            $category =
                                mysqli_fetch_assoc(
                                    $category_result
                                )
                        ): ?>

                            <option
                                value="<?php
                                    echo (int)$category[
                                        "category_id"
                                    ];
                                ?>"
                                <?php
                                echo (
                                    $category_id ==
                                    $category["category_id"]
                                ) ? "selected" : "";
                                ?>
                            >
                                <?php
                                echo htmlspecialchars(
                                    $category[
                                        "category_name"
                                    ]
                                );
                                ?>
                            </option>

                        <?php endwhile; ?>

                    </select>
                </div>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">
                            Price
                        </label>

                        <input
                            type="number"
                            name="price"
                            class="form-control"
                            value="<?php
                                echo htmlspecialchars($price);
                            ?>"
                            min="0.01"
                            step="0.01"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Stock Quantity
                        </label>

                        <input
                            type="number"
                            name="stock_quantity"
                            class="form-control"
                            value="<?php
                                echo htmlspecialchars(
                                    $stock_quantity
                                );
                            ?>"
                            min="0"
                            required
                        >
                    </div>

                </div>

                <div class="mt-3 mb-3">
                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="4"
                        required
                    ><?php
                        echo htmlspecialchars($description);
                    ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Product Image
                    </label>

                    <input
                        type="file"
                        name="product_image"
                        class="form-control"
                        accept=".jpg,.jpeg,.png"
                        <?php echo !$is_edit ? "required" : ""; ?>
                    >

                    <small>
                        JPG or PNG only. Maximum size: 2MB.
                        <?php if ($is_edit): ?>
                            Leave blank to keep the current image.
                        <?php endif; ?>
                    </small>
                </div>

                <div class="product-form-actions">
                    <a
                        href="stocks.php"
                        class="phserved-button phserved-button-secondary"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="phserved-button"
                    >
                        <?php
                        echo $is_edit
                            ? "Save Changes"
                            : "Add Product";
                        ?>
                    </button>
                </div>

            </form>

        </div>

    </main>

    <?php include("../includes/footer.php"); ?>

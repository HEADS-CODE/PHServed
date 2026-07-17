<?php

//Product details

$product_id = (int) $product["product_id"];
$product_name = $product["product_name"];
$category_name = $product["category_name"];
$product_price = (float) $product["price"];
$stock_quantity = (int) $product["stock_quantity"];
$product_status = $product["product_status"];
$product_description = $product["description"];

//Default product image
if (
    isset($product["product_image"]) &&
    $product["product_image"] != ""
) {
    $product_image =
        "../images/products/" .
        basename($product["product_image"]);
} else {
    $product_image =
        "../images/logo/phserved_logo.png";
}

//Stock status color
$status_class = "status-available";

if ($product_status == "Low Stock") {
    $status_class = "status-low";
}

if ($product_status == "Out of Stock") {
    $status_class = "status-out";
}

?>

<article class="store-product-card">

    <div class="product-image-area">
        <img src="<?php echo htmlspecialchars($product_image); ?>" alt="<?php echo htmlspecialchars($product_name); ?>"
            class="store-product-image">
    </div>

    <div class="product-card-body">

        <div class="product-card-top">
            <span class="product-category">
                <?php echo htmlspecialchars($category_name); ?>
            </span>

            <span class="product-status <?php echo $status_class; ?>">
                <?php echo htmlspecialchars(strtoupper($product_status)); ?>
            </span>
        </div>

        <h2 class="product-name">
            <?php echo htmlspecialchars($product_name); ?>
        </h2>

        <p class="product-description">
            <?php echo htmlspecialchars($product_description); ?>
        </p>

        <p class="product-stock">
            Stock available:
            <strong><?php echo $stock_quantity; ?></strong>
        </p>

        <p class="product-price">
            ₱<?php echo number_format($product_price, 2); ?>
        </p>

        <?php if ($stock_quantity > 0): ?>

            <form method="POST" action="add_to_cart.php" class="add-cart-form">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

                <input type="hidden" name="return_category" value="<?php
                echo htmlspecialchars(
                    isset($return_category)
                    ? $return_category
                    : ""
                );
                ?>">

                <div class="quantity-field">
                    <label for="quantity_<?php echo $product_id; ?>">
                        Quantity
                    </label>

                    <input type="number" name="quantity" id="quantity_<?php echo $product_id; ?>" value="1" min="1"
                        max="<?php echo $stock_quantity; ?>" required>
                </div>

                <button type="submit" class="phserved-button add-cart-button">
                    Add to Cart
                </button>
            </form>

        <?php else: ?>

            <button type="button" class="phserved-button unavailable-button" disabled>
                Out of Stock
            </button>

        <?php endif; ?>

    </div>

</article>
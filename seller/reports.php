<?php

require_once("includes/admin_required.php");
require_once("../config/db_connect.php");

$report_type = isset($_GET["report_type"])
    ? $_GET["report_type"]
    : "";

if (!in_array($report_type, array("", "inventory", "audit"))) {
    $report_type = "";
}

$report_generated =
    isset($_GET["generate"]) &&
    $_GET["generate"] == "1" &&
    $report_type != "";

$generated_at = null;

if ($report_generated) {
    $generated_at = new DateTimeImmutable(
        "now",
        new DateTimeZone("Asia/Manila")
    );
}

$category_id = isset($_GET["category_id"])
    ? (int)$_GET["category_id"]
    : 0;

$summary_sql =
    "SELECT
        COUNT(*) AS total_products,
        SUM(CASE WHEN stock_quantity > 5 THEN 1 ELSE 0 END) AS available_products,
        SUM(CASE WHEN stock_quantity BETWEEN 1 AND 5 THEN 1 ELSE 0 END) AS low_stock_products,
        SUM(CASE WHEN stock_quantity = 0 THEN 1 ELSE 0 END) AS out_of_stock_products
     FROM products";

$selected_category_name = "All Categories";

if ($category_id > 0) {
    $summary_sql .=
        " WHERE category_id = " . $category_id;

    $selected_category_result = mysqli_query(
        $conn,
        "SELECT category_name
         FROM categories
         WHERE category_id = $category_id"
    );

    if (
        $selected_category_result &&
        mysqli_num_rows($selected_category_result) == 1
    ) {
        $selected_category = mysqli_fetch_assoc(
            $selected_category_result
        );

        $selected_category_name =
            $selected_category["category_name"];
    }
}

$inventory_summary = array(
    "total_products" => 0,
    "available_products" => 0,
    "low_stock_products" => 0,
    "out_of_stock_products" => 0
);

$category_result = mysqli_query(
    $conn,
    "SELECT category_id, category_name
     FROM categories
     ORDER BY category_name"
);

$inventory_sql =
    "SELECT
        products.product_id,
        products.product_name,
        products.price,
        products.stock_quantity,
        products.product_status,
        categories.category_name
     FROM products
     INNER JOIN categories
        ON products.category_id = categories.category_id";

if ($category_id > 0) {
    $inventory_sql .=
        " WHERE products.category_id = " . $category_id;
}

$inventory_sql .=
    " ORDER BY categories.category_name, products.product_name";

$inventory_result = false;
$audit_result = false;

if ($report_generated && $report_type == "inventory") {
    $summary_result = mysqli_query($conn, $summary_sql);
    $inventory_summary = mysqli_fetch_assoc($summary_result);
    $inventory_result = mysqli_query($conn, $inventory_sql);
}

if ($report_generated && $report_type == "audit") {
    $current_admin_id = (int)$_SESSION["user_id"];

    $audit_result = mysqli_query(
        $conn,
        "SELECT
            audit_logs.log_id,
            audit_logs.action,
            audit_logs.action_date,
            users.complete_name,
            users.email
         FROM audit_logs
         INNER JOIN users
            ON audit_logs.user_id = users.user_id
         WHERE users.role = 'Admin'
            AND audit_logs.user_id = $current_admin_id
         ORDER BY audit_logs.action_date DESC, audit_logs.log_id DESC"
    );
}

$page_title = "Reports";
$active_page = "reports";

include("includes/header.php");
include("includes/sidebar.php");

?>

<div class="main-wrapper" id="mainWrapper">

    <?php include("includes/top_nav.php"); ?>

    <main class="page-content">

        <div class="reports-heading">
            <div>
                <h2>Seller Reports</h2>
                <p>Generate a current inventory or audit log report.</p>
            </div>
        </div>

        <form method="get" class="report-controls">
            <div class="report-field">
                <label for="reportType">Report Type</label>
                <select
                    name="report_type"
                    id="reportType"
                    class="form-select"
                    required
                >
                    <option value="" <?php echo $report_type == "" ? "selected" : ""; ?>>
                        Select Report Type
                    </option>
                    <option
                        value="inventory"
                        <?php echo $report_type == "inventory" ? "selected" : ""; ?>
                    >
                        Inventory
                    </option>
                    <option
                        value="audit"
                        <?php echo $report_type == "audit" ? "selected" : ""; ?>
                    >
                        Audit Log
                    </option>
                </select>
            </div>

            <?php if ($report_generated && $report_type == "inventory"): ?>
                <div class="report-field" id="inventoryCategoryField">
                    <label for="categoryId">Category</label>
                    <select
                        name="category_id"
                        id="categoryId"
                        class="form-select"
                    >
                        <option value="0">All Categories</option>

                        <?php while ($category = mysqli_fetch_assoc($category_result)): ?>
                            <option
                                value="<?php echo (int)$category["category_id"]; ?>"
                                <?php echo $category_id == $category["category_id"] ? "selected" : ""; ?>
                            >
                                <?php echo htmlspecialchars($category["category_name"]); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            <?php endif; ?>

            <input type="hidden" name="generate" value="1">

            <button type="submit" class="phserved-button report-button">
                Generate Report
            </button>
        </form>

        <?php if ($report_generated && $report_type == "inventory"): ?>

            <section class="report-section" aria-labelledby="inventoryTitle">
                <div class="report-title-row">
                    <div>
                        <h3 id="inventoryTitle">Inventory Report</h3>
                        <p>
                            Current product availability for
                            <strong><?php echo htmlspecialchars($selected_category_name); ?></strong>.
                        </p>
                    </div>

                    <span class="report-generated-date">
                        Generated <?php echo $generated_at->format("F j, Y - g:i A"); ?>
                    </span>
                </div>

                <div class="inventory-summary-grid">
                    <article class="inventory-summary-card summary-all-products">
                        <span>Total Products</span>
                        <strong><?php echo (int)$inventory_summary["total_products"]; ?></strong>
                    </article>

                    <article class="inventory-summary-card summary-available">
                        <span>Available Products</span>
                        <strong><?php echo (int)$inventory_summary["available_products"]; ?></strong>
                    </article>

                    <article class="inventory-summary-card summary-low">
                        <span>Low Stock Products</span>
                        <strong><?php echo (int)$inventory_summary["low_stock_products"]; ?></strong>
                    </article>

                    <article class="inventory-summary-card summary-out">
                        <span>Out of Stock Products</span>
                        <strong><?php echo (int)$inventory_summary["out_of_stock_products"]; ?></strong>
                    </article>
                </div>

                <div class="report-table-wrap">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Unit Price</th>
                                <th>Stock on Hand</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (mysqli_num_rows($inventory_result) > 0): ?>
                                <?php while ($product = mysqli_fetch_assoc($inventory_result)): ?>
                                    <?php
                                    $stock_quantity = (int)$product["stock_quantity"];

                                    if ($stock_quantity == 0) {
                                        $stock_status = "Out of Stock";
                                        $status_class = "status-out";
                                    } elseif ($stock_quantity <= 5) {
                                        $stock_status = "Low Stock";
                                        $status_class = "status-low";
                                    } else {
                                        $stock_status = "Available";
                                        $status_class = "status-available";
                                    }
                                    ?>

                                    <tr>
                                        <td>#<?php echo (int)$product["product_id"]; ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($product["product_name"]); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($product["category_name"]); ?></td>
                                        <td>&#8369;<?php echo number_format($product["price"], 2); ?></td>
                                        <td><?php echo $stock_quantity; ?></td>
                                        <td>
                                            <span class="report-status <?php echo $status_class; ?>">
                                                <?php echo $stock_status; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="report-empty">
                                        No products were found in this category.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        <?php elseif ($report_generated && $report_type == "audit"): ?>

            <section class="report-section" aria-labelledby="auditTitle">
                <div class="report-title-row">
                    <div>
                        <h3 id="auditTitle">Audit Log Report</h3>
                        <p>
                            Activities recorded for the currently signed-in administrator.
                        </p>
                    </div>

                    <span class="report-generated-date">
                        Generated <?php echo $generated_at->format("F j, Y - g:i A"); ?>
                    </span>
                </div>

                <div class="report-table-wrap">
                    <table class="report-table audit-report-table">
                        <thead>
                            <tr>
                                <th>Admin / User</th>
                                <th>Action</th>
                                <th>Date &amp; Time</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (mysqli_num_rows($audit_result) > 0): ?>
                                <?php while ($log = mysqli_fetch_assoc($audit_result)): ?>
                                    <tr>
                                        <td>
                                            <div class="audit-user">
                                                <span class="audit-user-icon" aria-hidden="true">
                                                    <?php echo htmlspecialchars(strtoupper(substr($log["complete_name"], 0, 1))); ?>
                                                </span>

                                                <div>
                                                    <strong><?php echo htmlspecialchars($log["complete_name"]); ?></strong>
                                                    <small><?php echo htmlspecialchars($log["email"]); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($log["action"]); ?></td>
                                        <td>
                                            <time datetime="<?php echo htmlspecialchars(date("c", strtotime($log["action_date"]))); ?>">
                                                <?php echo date("F j, Y", strtotime($log["action_date"])); ?>
                                                <small><?php echo date("g:i A", strtotime($log["action_date"])); ?></small>
                                            </time>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="report-empty">
                                        No administrator activities have been recorded yet.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        <?php endif; ?>

    </main>

<?php if ($report_generated && $report_type == "inventory"): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var reportType = document.getElementById("reportType");
            var categoryField = document.getElementById("inventoryCategoryField");

            reportType.addEventListener("change", function () {
                categoryField.hidden = reportType.value != "inventory";
            });
        });
    </script>
<?php endif; ?>

    <?php include("../includes/footer.php"); ?>

<?php

require_once(
    __DIR__ . "/../../config/db_connect.php"
);

$cart_count = 0;

if (isset($_SESSION["user_id"])) {
    $current_user_id =
        (int) $_SESSION["user_id"];

    $cart_count_sql =
        "SELECT SUM(quantity) AS total_quantity
         FROM cart_items
         WHERE user_id = $current_user_id";

    $cart_count_result =
        mysqli_query($conn, $cart_count_sql);

    if ($cart_count_result) {
        $cart_count_row =
            mysqli_fetch_assoc($cart_count_result);

        if ($cart_count_row["total_quantity"] != null) {
            $cart_count =
                (int) $cart_count_row["total_quantity"];
        }
    }
}

?>
<header class="top-navigation">

    <div class="top-navigation-left">
        <button type="button" class="mobile-menu-button" id="mobileMenuButton" aria-label="Open menu">
            &#9776;
        </button>

        <h1 class="page-title">
            <?php echo htmlspecialchars($page_title); ?>
        </h1>
    </div>

    <div class="top-navigation-right">

        <?php if (isset($_SESSION["user_id"])): ?>

            <div class="dropdown">
                <button class="btn dropdown-toggle navigation-link" type="button" data-bs-toggle="dropdown">
                    <img src="../images/icons/account.png" alt="" class="navigation-icon">
                    Account
                </button>

                <div class="dropdown-menu dropdown-menu-end account-box">
                    <p class="account-name">
                        <?php
                        echo htmlspecialchars(
                            $_SESSION["complete_name"]
                        );
                        ?>
                    </p>

                    <p class="account-email">
                        <?php
                        echo htmlspecialchars(
                            $_SESSION["email"]
                        );
                        ?>
                    </p>

                    <a href="logout.php" class="phserved-button w-100 text-center">
                        LOG OUT
                    </a>
                </div>
            </div>

        <?php else: ?>

            <a href="login.php" class="navigation-link">
                <img src="../images/icons/account.png" alt="" class="navigation-icon">
                Register / Sign In
            </a>

        <?php endif; ?>

        <a href="cart.php" class="navigation-link">
            <img src="../images/icons/cart.png" alt="" class="navigation-icon">
            Cart (<?php echo $cart_count; ?>)
        </a>

    </div>

</header>
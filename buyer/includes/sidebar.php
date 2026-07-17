<?php

//Guest's name
$buyer_name = "Guest";

if (isset($_SESSION["complete_name"])) {
    $buyer_name = $_SESSION["complete_name"];
}

?>

<aside class="side-panel" id="sidebar">

    <button type="button" class="sidebar-toggle" id="sidebarButton" aria-label="Close or open sidebar">
        <img src="../images/icons/sidebar_arrow.png" alt="">
    </button>

    <div class="sidebar-logo">
        <img src="../images/logo/phserved_logo.png" alt="PHServed logo">

        <p class="system-name">
            COMPUTER PARTS E-COMMERCE ONLINE MARKETPLACE
        </p>
    </div>

    <p class="welcome-text">
        HELLO,
        <?php echo htmlspecialchars(strtoupper($buyer_name)); ?>!
    </p>

    <nav>
        <p class="sidebar-heading">Market</p>

        <ul class="sidebar-menu">
            <li>
                <a href="index.php" class="<?php echo $active_page == "store" ? "active" : ""; ?>">
                    <img src="../images/icons/store.png" alt="" class="sidebar-icon">
                    <span>Store</span>
                </a>
            </li>

            <li>
                <a href="orders.php" class="<?php echo $active_page == "orders" ? "active" : ""; ?>">
                    <img src="../images/icons/cart.png" alt="" class="sidebar-icon">
                    <span>Orders</span>
                </a>
            </li>

        </ul>

        <p class="sidebar-heading">Categories</p>

        <ul class="sidebar-menu">
            <li>
                <a href="category.php?category=Input+Devices">
                    <img src="../images/icons/input_devices.png" alt="" class="sidebar-icon">
                    <span>Input Devices</span>
                </a>
            </li>

            <li>
                <a href="category.php?category=Processing+Devices">
                    <img src="../images/icons/processing_devices.png" alt="" class="sidebar-icon">
                    <span>Processing Devices</span>
                </a>
            </li>

            <li>
                <a href="category.php?category=Output+Devices">
                    <img src="../images/icons/output_devices.png" alt="" class="sidebar-icon">
                    <span>Output Devices</span>
                </a>
            </li>
        </ul>

        <p class="sidebar-heading">Company</p>

        <ul class="sidebar-menu">
            <li>
                <a href="about.php" class="<?php echo $active_page == "about" ? "active" : ""; ?>">
                    <img src="../images/icons/about_us.png" alt="" class="sidebar-icon">
                    <span>About Us</span>
                </a>
            </li>
        </ul>
    </nav>

</aside>
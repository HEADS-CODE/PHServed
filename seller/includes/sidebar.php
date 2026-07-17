<?php

$admin_name = "ADMIN";

if (isset($_SESSION["complete_name"])) {
    $admin_name = $_SESSION["complete_name"];
}

?>

<aside class="side-panel" id="sidebar">

    <button
        type="button"
        class="sidebar-toggle"
        id="sidebarButton"
        aria-label="Close or open sidebar"
    >
        <img
            src="../images/icons/seller_sidebar_arrow.png"
            alt=""
        >
    </button>

    <div class="sidebar-logo">
        <img
            src="../images/logo/phserved_logo.png"
            alt="PHServed logo"
        >

        <p class="system-name">
            COMPUTER PARTS E-COMMERCE SYSTEM ADMINISTRATION
        </p>
    </div>

    <p class="welcome-text">
        HELLO,
        <?php echo htmlspecialchars(strtoupper($admin_name)); ?>!
    </p>

    <nav>
        <p class="sidebar-heading">Management</p>

        <ul class="sidebar-menu">
            <li>
                <a
                    href="users.php"
                    class="<?php echo $active_page == "users" ? "active" : ""; ?>"
                >
                    <img
                        src="../images/icons/users.png"
                        alt=""
                        class="sidebar-icon"
                    >
                    <span>Users</span>
                </a>
            </li>

            <li>
                <a
                    href="stocks.php"
                    class="<?php echo $active_page == "stocks" ? "active" : ""; ?>"
                >
                    <img
                        src="../images/icons/stocks.png"
                        alt=""
                        class="sidebar-icon"
                    >
                    <span>Stocks</span>
                </a>
            </li>

            <li>
                <a
                    href="reports.php"
                    class="<?php echo $active_page == "reports" ? "active" : ""; ?>"
                >
                    <img
                        src="../images/icons/reports.png"
                        alt=""
                        class="sidebar-icon"
                    >
                    <span>Reports</span>
                </a>
            </li>
        </ul>

        <p class="sidebar-heading">Company</p>

        <ul class="sidebar-menu">
            <li>
                <a
                    href="about.php"
                    class="<?php echo $active_page == "about" ? "active" : ""; ?>"
                >
                    <img
                        src="../images/icons/seller_about_us.png"
                        alt=""
                        class="sidebar-icon"
                    >
                    <span>About Us</span>
                </a>
            </li>
        </ul>
    </nav>

</aside>

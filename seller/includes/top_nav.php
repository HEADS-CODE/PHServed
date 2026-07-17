<header class="top-navigation">

    <div class="top-navigation-left">
        <button
            type="button"
            class="mobile-menu-button"
            id="mobileMenuButton"
            aria-label="Open menu"
        >
            &#9776;
        </button>

        <h1 class="page-title">
            <?php echo htmlspecialchars($page_title); ?>
        </h1>
    </div>

    <div class="top-navigation-right">

        <div class="dropdown">
            <button
                class="btn dropdown-toggle navigation-link"
                type="button"
                data-bs-toggle="dropdown"
            >
                <img
                    src="../images/icons/seller_account.png"
                    alt=""
                    class="navigation-icon"
                >
                Account
            </button>

            <div class="dropdown-menu dropdown-menu-end account-box">
                <p class="account-name">
                    <?php
                    echo htmlspecialchars(
                        isset($_SESSION["complete_name"])
                            ? $_SESSION["complete_name"]
                            : "Administrator"
                    );
                    ?>
                </p>

                <p class="account-email">
                    <?php
                    echo htmlspecialchars(
                        isset($_SESSION["email"])
                            ? $_SESSION["email"]
                            : ""
                    );
                    ?>
                </p>

                <a
                    href="logout.php"
                    class="phserved-button w-100 text-center"
                >
                    LOG OUT
                </a>
            </div>
        </div>

    </div>

</header>

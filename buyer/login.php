<?php

session_set_cookie_params(0, dirname($_SERVER["SCRIPT_NAME"]));
session_name("phserved_buyer_session");
session_start();

require_once("../config/db_connect.php");

$message = "";

$login_notice = "";

if (isset($_SESSION["login_notice"])) {
    $login_notice = $_SESSION["login_notice"];
    unset($_SESSION["login_notice"]);
}

$remembered_email = isset($_COOKIE["remember_email"])
    ? $_COOKIE["remember_email"]
    : "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $email = mysqli_real_escape_string(
        $conn,
        $email
    );

    $sql =
        "SELECT *
         FROM users
         WHERE email = '$email'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        $password_is_correct = password_verify(
            $password,
            $user["password"]
        );

        if (!$password_is_correct) {
            $message = "Incorrect email or password.";
        } elseif ($user["account_status"] != "Active") {
            $message =
                "This account is currently inactive.";
        } elseif ($user["is_confirmed"] != 1) {
            $message =
                "Please confirm your email before signing in.";
        } else {
            //Login session
            $_SESSION["user_id"] =
                $user["user_id"];

            $_SESSION["complete_name"] =
                $user["complete_name"];

            $_SESSION["email"] =
                $user["email"];

            $_SESSION["role"] =
                $user["role"];

            //Remembered email
            if (isset($_POST["remember_email"])) {
                setcookie(
                    "remember_email",
                    $user["email"],
                    time() + (30 * 24 * 60 * 60),
                    "/"
                );
            } else {
                setcookie(
                    "remember_email",
                    "",
                    time() - 3600,
                    "/"
                );
            }

            $return_page = "index.php";

            if (isset($_SESSION["return_after_login"])) {
                $return_page =
                    $_SESSION["return_after_login"];

                unset($_SESSION["return_after_login"]);

                $_SESSION["cart_message"] =
                    "Successfully signed in! You can now add to your cart " .
                    "and purchase your desired products.";

                $_SESSION["cart_message_type"] =
                    "info";
            }

            header("Location: " . $return_page);
            exit;
        }
    } else {
        $message = "Incorrect email or password.";
    }
}

$page_title = "Buyer Login";
$auth_type = "buyer";

include("../includes/auth_header.php");

?>

<div class="auth-container">
    <div class="auth-card login-auth-card">

        <h1 class="auth-title">Sign In</h1>

        <p class="auth-description">
            Sign in to use your cart and to process your orders.
        </p>

        <?php if ($login_notice != ""): ?>
            <script>
                alert(<?php echo json_encode($login_notice); ?>);
            </script>
        <?php endif; ?>

        <?php if ($message != ""): ?>
            <script>
                alert(<?php echo json_encode($message); ?>);
            </script>
        <?php endif; ?>

        <form method="POST" action="login.php">

            <div class="mb-3">
                <label class="form-label">
                    Email Address
                </label>

                <input type="email" name="email" class="form-control" value="<?php
                echo htmlspecialchars(
                    $remembered_email
                );
                ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Password
                </label>

                <input type="password" name="password" id="buyerLoginPassword" class="form-control" required>
            </div>

            <div class="d-flex justify-content-between mb-4">
                <div class="form-check">
                    <input type="checkbox" name="remember_email" id="buyerRemember" class="form-check-input" <?php
                    echo $remembered_email != ""
                        ? "checked"
                        : "";
                    ?>>

                    <label for="buyerRemember" class="form-check-label">
                        Remember my email
                    </label>
                </div>

                <div class="form-check">
                    <input type="checkbox" id="showBuyerPassword" class="form-check-input">

                    <label for="showBuyerPassword" class="form-check-label">
                        Show password
                    </label>
                </div>
            </div>

            <button type="submit" class="phserved-button w-100">
                Sign In
            </button>

        </form>

        <p class="text-center mt-4 mb-0">
            New to PHServed?

            <a href="register.php" class="auth-link">
                Register
            </a>
        </p>

        <p class="text-center mt-2 mb-0">
            <a href="index.php" class="auth-link">
                Continue as guest
            </a>
        </p>

    </div>
</div>

<script>
    document
        .getElementById("showBuyerPassword")
        .addEventListener("change", function () {
            var password =
                document.getElementById("buyerLoginPassword");

            password.type =
                this.checked ? "text" : "password";
        });
</script>

<?php include("../includes/footer.php"); ?>
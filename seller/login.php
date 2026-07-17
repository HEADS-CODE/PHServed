<?php

session_set_cookie_params(0, dirname($_SERVER["SCRIPT_NAME"]));
session_name("phserved_seller_session");
session_start();

require_once("../config/db_connect.php");

$message = "";

$remembered_email = isset($_COOKIE["admin_email"])
    ? $_COOKIE["admin_email"]
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
         WHERE email = '$email'
         AND role = 'Admin'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);

        $password_is_correct = password_verify(
            $password,
            $admin["password"]
        );

        if (!$password_is_correct) {
            $message =
                "Incorrect credentials, please enter your email or password again.";
        } elseif ($admin["account_status"] != "Active") {
            $message =
                "This administrator account is inactive.";
        } elseif ($admin["is_confirmed"] != 1) {
            $message =
                "This administrator account is not confirmed.";
        } else {
            $_SESSION["user_id"] =
                $admin["user_id"];

            $_SESSION["complete_name"] =
                $admin["complete_name"];

            $_SESSION["email"] =
                $admin["email"];

            $_SESSION["role"] =
                $admin["role"];

            if (isset($_POST["remember_email"])) {
                setcookie(
                    "admin_email",
                    $admin["email"],
                    time() + (30 * 24 * 60 * 60),
                    "/"
                );
            } else {
                setcookie(
                    "admin_email",
                    "",
                    time() - 3600,
                    "/"
                );
            }

            //Login record
            $admin_id = $admin["user_id"];

            $log_sql =
                "INSERT INTO audit_logs (
                    user_id,
                    action
                ) VALUES (
                    $admin_id,
                    'Logged in to the Seller System'
                )";

            mysqli_query($conn, $log_sql);

            header("Location: users.php");
            exit;
        }
    } else {
        $message =
            "Incorrect credentials, please enter your email or password again.";
    }
}

$page_title = "Seller Login";
$auth_type = "seller";

include("../includes/auth_header.php");

?>

<div class="auth-container">
    <div class="auth-card login-auth-card">

        <h1 class="auth-title">Sign in, fellow PHS Admin!</h1>

        <p class="auth-description">
            Only authorized PHServed administrators may proceed.<br>
            <a href="../buyer/about.php" class="seller-contact-link">
                Contact Us for inquiries.
            </a>
        </p>

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

                <input type="password" name="password" id="sellerLoginPassword" class="form-control" required>
            </div>

            <div class="d-flex justify-content-between mb-4">
                <div class="form-check">
                    <input type="checkbox" name="remember_email" id="sellerRemember" class="form-check-input" <?php
                    echo $remembered_email != ""
                        ? "checked"
                        : "";
                    ?>>

                    <label for="sellerRemember" class="form-check-label">
                        Remember my email
                    </label>
                </div>

                <div class="form-check">
                    <input type="checkbox" id="showSellerPassword" class="form-check-input">

                    <label for="showSellerPassword" class="form-check-label">
                        Show password
                    </label>
                </div>
            </div>

            <button type="submit" class="phserved-button seller-login-button w-100">
                Sign in
            </button>

        </form>

        <p class="text-center mt-4 mb-0">
            <a href="../buyer/index.php" class="auth-link seller-return-link">
                Return to Store
            </a>
        </p>

    </div>
</div>

<script>
    document
        .getElementById("showSellerPassword")
        .addEventListener("change", function () {
            var password =
                document.getElementById("sellerLoginPassword");

            password.type =
                this.checked ? "text" : "password";
        });
</script>

<?php include("../includes/footer.php"); ?>
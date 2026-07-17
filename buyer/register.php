<?php

require_once("../config/db_connect.php");
require_once("../config/mailer.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Registration form

    $complete_name = trim(
        $_POST["complete_name"] ?? ""
    );

    $email = trim(
        $_POST["email"] ?? ""
    );

    $password =
        $_POST["password"] ?? "";

    $confirm_password =
        $_POST["confirm_password"] ?? "";

    $street = trim(
        $_POST["street"] ?? ""
    );

    $barangay = trim(
        $_POST["barangay"] ?? ""
    );

    $city = trim(
        $_POST["city"] ?? ""
    );

    $province = trim(
        $_POST["province"] ?? ""
    );

    $zip_code = trim(
        $_POST["zip_code"] ?? ""
    );

    //Contact number
    $contact_input = trim(
        $_POST["contact_number"] ?? ""
    );

    //Philippine number
    $contact_number = "+63" . $contact_input;

    //Required fields
    if (
        $complete_name == "" ||
        $email == "" ||
        $password == "" ||
        $confirm_password == "" ||
        $street == "" ||
        $barangay == "" ||
        $city == "" ||
        $province == "" ||
        $zip_code == "" ||
        $contact_input == ""
    ) {
        $message = "Please complete all required fields.";
    }

    //Email check
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
    }

    //Password check
    elseif ($password !== $confirm_password) {
        $message = "The passwords do not match.";
    }

    //Mobile number check
    elseif (
        !preg_match(
            "/^9[0-9]{9}$/",
            $contact_input
        )
    ) {
        $message =
            "Enter exactly 10 digits beginning with 9.";
    } else {
        //Form values
        $complete_name = mysqli_real_escape_string(
            $conn,
            $complete_name
        );

        $email = mysqli_real_escape_string(
            $conn,
            $email
        );

        $password = mysqli_real_escape_string(
            $conn,
            password_hash($password, PASSWORD_DEFAULT)
        );

        $street = mysqli_real_escape_string(
            $conn,
            $street
        );

        $barangay = mysqli_real_escape_string(
            $conn,
            $barangay
        );

        $city = mysqli_real_escape_string(
            $conn,
            $city
        );

        $province = mysqli_real_escape_string(
            $conn,
            $province
        );

        $zip_code = mysqli_real_escape_string(
            $conn,
            $zip_code
        );

        $contact_number = mysqli_real_escape_string(
            $conn,
            $contact_number
        );

        //Existing email
        $check_sql =
            "SELECT user_id
             FROM users
             WHERE email = '$email'";

        $check_result = mysqli_query(
            $conn,
            $check_sql
        );

        if (mysqli_num_rows($check_result) > 0) {
            $message =
                "That email address is already registered.";
        } else {
            //Confirmation token
            $confirm_token =
                bin2hex(random_bytes(32));

            $confirm_token =
                mysqli_real_escape_string(
                    $conn,
                    $confirm_token
                );

            //Buyer role
            $insert_sql =
                "INSERT INTO users (
                    complete_name,
                    email,
                    password,
                    street,
                    barangay,
                    city,
                    province,
                    zip_code,
                    contact_number,
                    role,
                    account_status,
                    is_confirmed,
                    confirm_token
                ) VALUES (
                    '$complete_name',
                    '$email',
                    '$password',
                    '$street',
                    '$barangay',
                    '$city',
                    '$province',
                    '$zip_code',
                    '$contact_number',
                    'Buyer',
                    'Active',
                    0,
                    '$confirm_token'
                )";

            if (mysqli_query($conn, $insert_sql)) {
                try {
                    send_confirmation_email(
                        $email,
                        $complete_name,
                        $confirm_token
                    );

                    $message =
                        "Registration was successful. " .
                        "Please check your email and confirm your account.";

                } catch (Exception $error) {
                    error_log($error->getMessage());

                    mysqli_query(
                        $conn,
                        "DELETE FROM users
                         WHERE email = '$email'
                         AND is_confirmed = 0"
                    );

                    $message =
                        "The confirmation email could not be sent. " .
                        "Please check the email settings and try again.";

                }
            } else {
                error_log(mysqli_error($conn));
                $message = "Registration could not be completed. Please try again.";
            }
        }
    }
}

$page_title = "Buyer Registration";
$auth_type = "buyer";

include("../includes/auth_header.php");

?>

<div class="auth-container">
    <div class="auth-card registration-auth-card">

        <h1 class="auth-title">Create Account</h1>

        <p class="auth-description">
            Register to save your cart and complete your orders.
        </p>

        <?php if ($message != ""): ?>
            <script>
                alert(<?php echo json_encode($message); ?>);
            </script>
        <?php endif; ?>

        <form method="POST" action="register.php">

            <div class="mb-3">
                <label class="form-label">
                    Complete Name
                    <span class="required-mark">*</span>
                </label>

                <input type="text" name="complete_name" class="form-control" value="<?php
                echo isset($_POST["complete_name"])
                    ? htmlspecialchars($_POST["complete_name"])
                    : "";
                ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Email Address
                    <span class="required-mark">*</span>
                </label>

                <input type="email" name="email" class="form-control" value="<?php
                echo isset($_POST["email"])
                    ? htmlspecialchars($_POST["email"])
                    : "";
                ?>" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Password
                        <span class="required-mark">*</span>
                    </label>

                    <input type="password" name="password" id="registerPassword" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Confirm Password
                        <span class="required-mark">*</span>
                    </label>

                    <input type="password" name="confirm_password" id="registerConfirmPassword" class="form-control"
                        required>
                </div>
            </div>

            <div class="form-check mb-4">
                <input type="checkbox" class="form-check-input" id="showRegisterPasswords">

                <label for="showRegisterPasswords" class="form-check-label">
                    Show passwords
                </label>
            </div>

            <h2 class="h5 fw-bold mb-3">Complete Address</h2>

            <div class="mb-3">
                <label class="form-label">
                    Street / House Number
                    <span class="required-mark">*</span>
                </label>

                <input type="text" name="street" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Region
                    <span class="required-mark">*</span>
                </label>

                <select name="region" id="region" class="form-select" disabled required>
                    <option value="">Select Region</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Province
                    <span class="required-mark">*</span>
                </label>

                <select name="province" id="province" class="form-select" disabled required>
                    <option value="">Select Province</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    City / Municipality
                    <span class="required-mark">*</span>
                </label>

                <select name="city" id="city" class="form-select" disabled required>
                    <option value="">
                        Select City / Municipality
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Barangay
                    <span class="required-mark">*</span>
                </label>

                <select name="barangay" id="barangay" class="form-select" disabled required>
                    <option value="">Select Barangay</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    ZIP Code
                    <span class="required-mark">*</span>
                </label>

                <input type="text" name="zip_code" class="form-control" maxlength="10" required>
            </div>

            <div class="mb-4">
                <label class="form-label">
                    Philippine Mobile Number
                    <span class="required-mark">*</span>
                </label>

                <div class="input-group">
                    <span class="input-group-text">
                        +63
                    </span>

                    <input type="text" name="contact_number" class="form-control" placeholder="9#########"
                        pattern="9[0-9]{9}" maxlength="10" minlength="10" inputmode="numeric"
                        title="Enter exactly 10 digits beginning with 9."
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" value="<?php
                        echo isset($_POST["contact_number"])
                            ? htmlspecialchars($_POST["contact_number"])
                            : "";
                        ?>" required>
                </div>

                <div class="form-text">
                    Enter exactly 10 digits beginning with 9.
                    Example: 9000000000
                </div>
            </div>

            <button type="submit" class="phserved-button w-100">
                Create Account
            </button>

        </form>

        <p class="text-center mt-4 mb-0">
            Already registered?

            <a href="login.php" class="auth-link">
                Sign in
            </a>
        </p>

        <p class="text-center mt-2 mb-0">
            <a href="index.php" class="auth-link">
                Return to Store
            </a>
        </p>

    </div>
</div>

<script src="../assets/js/address.js"></script>

<script>
    document
        .getElementById("showRegisterPasswords")
        .addEventListener("change", function () {
            var password =
                document.getElementById("registerPassword");

            var confirmPassword =
                document.getElementById(
                    "registerConfirmPassword"
                );

            if (this.checked) {
                password.type = "text";
                confirmPassword.type = "text";
            } else {
                password.type = "password";
                confirmPassword.type = "password";
            }
        });
</script>

<?php include("../includes/footer.php"); ?>
<?php

require_once("includes/admin_required.php");
require_once("../config/db_connect.php");

$admin_id = (int)$_SESSION["user_id"];

$user_id = isset($_GET["id"])
    ? (int)$_GET["id"]
    : 0;

$is_edit = $user_id > 0;

$complete_name = "";
$email = "";
$street = "";
$barangay = "";
$city = "";
$province = "";
$zip_code = "";
$contact_input = "";
$role = "Buyer";
$account_status = "Active";
$is_confirmed = 1;
$error_message = "";

/* User details */

if ($is_edit) {
    $find_sql =
        "SELECT *
         FROM users
         WHERE user_id = $user_id";

    $find_result =
        mysqli_query($conn, $find_sql);

    if (mysqli_num_rows($find_result) != 1) {
        header("Location: users.php");
        exit;
    }

    $current_user =
        mysqli_fetch_assoc($find_result);

    $complete_name =
        $current_user["complete_name"];

    $email =
        $current_user["email"];

    $street =
        $current_user["street"];

    $barangay =
        $current_user["barangay"];

    $city =
        $current_user["city"];

    $province =
        $current_user["province"];

    $zip_code =
        $current_user["zip_code"];

    $contact_input =
        preg_replace(
            "/^\+63/",
            "",
            $current_user["contact_number"]
        );

    $role =
        $current_user["role"];

    $account_status =
        $current_user["account_status"];

    $is_confirmed =
        (int)$current_user["is_confirmed"];
}

/* User form */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complete_name =
        trim($_POST["complete_name"] ?? "");

    $email =
        trim($_POST["email"] ?? "");

    $password =
        $_POST["password"] ?? "";

    $confirm_password =
        $_POST["confirm_password"] ?? "";

    $street =
        trim($_POST["street"] ?? "");

    $barangay =
        trim($_POST["barangay"] ?? "");

    $city =
        trim($_POST["city"] ?? "");

    $province =
        trim($_POST["province"] ?? "");

    $zip_code =
        trim($_POST["zip_code"] ?? "");

    $contact_input =
        trim($_POST["contact_number"] ?? "");

    $role =
        $_POST["role"] ?? "Buyer";

    $account_status =
        $_POST["account_status"] ?? "Active";

//Current admin account
    if ($is_edit && $user_id == $admin_id) {
        $role = $current_user["role"];
        $account_status =
            $current_user["account_status"];
    }

    if (
        $complete_name == "" ||
        $email == "" ||
        $street == "" ||
        $barangay == "" ||
        $city == "" ||
        $province == "" ||
        $zip_code == "" ||
        $contact_input == ""
    ) {
        $error_message =
            "Please complete all required fields.";
    } elseif (
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        $error_message =
            "Please enter a valid email address.";
    } elseif (
        !$is_edit &&
        $password == ""
    ) {
        $error_message =
            "A password is required for a new user.";
    } elseif (
        $password !== $confirm_password
    ) {
        $error_message =
            "Password and confirm password must match.";
    } elseif (
        !preg_match(
            "/^9[0-9]{9}$/",
            $contact_input
        )
    ) {
        $error_message =
            "Enter exactly 10 mobile-number digits beginning with 9.";
    } elseif (
        $is_edit &&
        $role == "Admin" &&
        $is_confirmed != 1
    ) {
        $error_message =
            "The account must confirm its email before becoming an administrator.";
    } else {
        $db_name =
            mysqli_real_escape_string(
                $conn,
                $complete_name
            );

        $db_email =
            mysqli_real_escape_string(
                $conn,
                $email
            );

        $db_street =
            mysqli_real_escape_string(
                $conn,
                $street
            );

        $db_barangay =
            mysqli_real_escape_string(
                $conn,
                $barangay
            );

        $db_city =
            mysqli_real_escape_string(
                $conn,
                $city
            );

        $db_province =
            mysqli_real_escape_string(
                $conn,
                $province
            );

        $db_zip =
            mysqli_real_escape_string(
                $conn,
                $zip_code
            );

        $db_contact =
            mysqli_real_escape_string(
                $conn,
                "+63" . $contact_input
            );

//Existing email
        $email_sql =
            "SELECT user_id
             FROM users
             WHERE email = '$db_email'
             AND user_id != $user_id";

        $email_result =
            mysqli_query($conn, $email_sql);

        if (mysqli_num_rows($email_result) > 0) {
            $error_message =
                "That email address is already registered.";
        } elseif ($is_edit) {
            $password_part = "";

            if ($password != "") {
                $db_password =
                    mysqli_real_escape_string(
                        $conn,
                        password_hash(
                            $password,
                            PASSWORD_DEFAULT
                        )
                    );

                $password_part =
                    ", password = '$db_password'";
            }

            $update_sql =
                "UPDATE users
                 SET
                    complete_name = '$db_name',
                    email = '$db_email',
                    street = '$db_street',
                    barangay = '$db_barangay',
                    city = '$db_city',
                    province = '$db_province',
                    zip_code = '$db_zip',
                    contact_number = '$db_contact',
                    role = '$role',
                    account_status = '$account_status'
                    $password_part
                 WHERE user_id = $user_id";

            mysqli_query($conn, $update_sql);

//Current user session
            if ($user_id == $admin_id) {
                $_SESSION["complete_name"] =
                    $complete_name;

                $_SESSION["email"] =
                    $email;
            }

            $action =
                mysqli_real_escape_string(
                    $conn,
                    "Updated user: " . $complete_name
                );

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

            $_SESSION["user_message"] =
                $complete_name .
                " was updated.";

            header("Location: users.php");
            exit;
        } else {
            $db_password =
                mysqli_real_escape_string(
                    $conn,
                    password_hash(
                        $password,
                        PASSWORD_DEFAULT
                    )
                );

//Confirmed user
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
                    is_confirmed
                ) VALUES (
                    '$db_name',
                    '$db_email',
                    '$db_password',
                    '$db_street',
                    '$db_barangay',
                    '$db_city',
                    '$db_province',
                    '$db_zip',
                    '$db_contact',
                    '$role',
                    '$account_status',
                    1
                )";

            mysqli_query($conn, $insert_sql);

            $action =
                mysqli_real_escape_string(
                    $conn,
                    "Added user: " .
                    $complete_name .
                    " as " .
                    $role
                );

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

            $_SESSION["user_message"] =
                $complete_name .
                " was added.";

            header("Location: users.php");
            exit;
        }
    }
}

$page_title =
    $is_edit ? "Edit User" : "Add User";

$active_page = "users";

include("includes/header.php");
include("includes/sidebar.php");

?>

<div class="main-wrapper" id="mainWrapper">

    <?php include("includes/top_nav.php"); ?>

    <main class="page-content">

        <div class="user-form-card">

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
                $user_id == $admin_id
            ): ?>

                <p class="user-form-note">
                    You may edit your information, but you cannot
                    remove your own Admin role or deactivate your
                    own account.
                </p>

            <?php endif; ?>

            <form
                method="POST"
                action="user_form.php<?php
                    echo $is_edit
                        ? "?id=" . $user_id
                        : "";
                ?>"
            >

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">
                            Complete Name
                        </label>

                        <input
                            type="text"
                            name="complete_name"
                            class="form-control"
                            value="<?php
                                echo htmlspecialchars(
                                    $complete_name
                                );
                            ?>"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Email Address
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="<?php
                                echo htmlspecialchars($email);
                            ?>"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            <?php echo !$is_edit ? "required" : ""; ?>
                        >

                        <?php if ($is_edit): ?>
                            <small>
                                Leave blank to keep the current password.
                            </small>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Confirm Password
                        </label>

                        <input
                            type="password"
                            name="confirm_password"
                            class="form-control"
                            <?php echo !$is_edit ? "required" : ""; ?>
                        >

                        <?php if ($is_edit): ?>
                            <small>
                                Required only when changing the password.
                            </small>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Philippine Mobile Number
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                +63
                            </span>

                            <input
                                type="text"
                                name="contact_number"
                                class="form-control"
                                value="<?php
                                    echo htmlspecialchars(
                                        $contact_input
                                    );
                                ?>"
                                pattern="9[0-9]{9}"
                                minlength="10"
                                maxlength="10"
                                required
                            >
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">
                            Street / House Number
                        </label>

                        <input
                            type="text"
                            name="street"
                            class="form-control"
                            value="<?php
                                echo htmlspecialchars($street);
                            ?>"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Region
                        </label>

                        <select
                            name="region"
                            id="region"
                            class="form-select"
                            disabled
                            required
                        >
                            <option value="">Select Region</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Province
                        </label>

                        <select
                            name="province"
                            id="province"
                            class="form-select"
                            data-selected="<?php echo htmlspecialchars($province); ?>"
                            disabled
                            required
                        >
                            <option value="">Select Province</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            City / Municipality
                        </label>

                        <select
                            name="city"
                            id="city"
                            class="form-select"
                            data-selected="<?php echo htmlspecialchars($city); ?>"
                            disabled
                            required
                        >
                            <option value="">Select City / Municipality</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Barangay
                        </label>

                        <select
                            name="barangay"
                            id="barangay"
                            class="form-select"
                            data-selected="<?php echo htmlspecialchars($barangay); ?>"
                            disabled
                            required
                        >
                            <option value="">Select Barangay</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            ZIP Code
                        </label>

                        <input
                            type="text"
                            name="zip_code"
                            class="form-control"
                            value="<?php
                                echo htmlspecialchars($zip_code);
                            ?>"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Role
                        </label>

                        <select
                            name="role"
                            class="form-select"
                            <?php
                            echo (
                                $is_edit &&
                                $user_id == $admin_id
                            ) ? "disabled" : "";
                            ?>
                        >
                            <option
                                value="Buyer"
                                <?php
                                echo $role == "Buyer"
                                    ? "selected"
                                    : "";
                                ?>
                            >
                                Buyer
                            </option>

                            <option
                                value="Admin"
                                <?php
                                echo $role == "Admin"
                                    ? "selected"
                                    : "";
                                ?>
                            >
                                Admin
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Account Status
                        </label>

                        <select
                            name="account_status"
                            class="form-select"
                            <?php
                            echo (
                                $is_edit &&
                                $user_id == $admin_id
                            ) ? "disabled" : "";
                            ?>
                        >
                            <option
                                value="Active"
                                <?php
                                echo $account_status == "Active"
                                    ? "selected"
                                    : "";
                                ?>
                            >
                                Active
                            </option>

                            <option
                                value="Inactive"
                                <?php
                                echo $account_status == "Inactive"
                                    ? "selected"
                                    : "";
                                ?>
                            >
                                Inactive
                            </option>
                        </select>
                    </div>

                </div>

                <div class="user-form-actions">
                    <a
                        href="users.php"
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
                            : "Add User";
                        ?>
                    </button>
                </div>

            </form>

        </div>

    </main>

    <script src="../assets/js/address.js"></script>

    <?php include("../includes/footer.php"); ?>

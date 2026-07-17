<?php

require_once("../config/db_connect.php");

$message = "";
$message_type = "error";

if (isset($_GET["token"])) {
    $token = mysqli_real_escape_string(
        $conn,
        $_GET["token"]
    );

    $find_sql =
        "SELECT user_id
         FROM users
         WHERE confirm_token = '$token'
         AND is_confirmed = 0";

    $find_result = mysqli_query(
        $conn,
        $find_sql
    );

    if (mysqli_num_rows($find_result) == 1) {
        $user = mysqli_fetch_assoc($find_result);
        $user_id = $user["user_id"];

        $update_sql =
            "UPDATE users
             SET is_confirmed = 1,
                 confirm_token = NULL
             WHERE user_id = $user_id";

        if (mysqli_query($conn, $update_sql)) {
            $message =
                "Your PHServed account is now confirmed. " .
                "You may sign in.";
            $message_type = "success";

        } else {
            $message =
                "The account could not be confirmed.";
        }
    } else {
        $message =
            "This confirmation link is invalid or was already used.";
    }
} else {
    $message = "No confirmation token was provided.";
}

$page_title = "Confirm Account";
$auth_type = "buyer";

include("../includes/auth_header.php");

?>

<div class="auth-container">
    <div class="auth-card text-center">

        <h1 class="auth-title">Account Confirmation</h1>

        <p class="confirmation-message confirmation-message-<?php
        echo $message_type;
        ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>

        <a href="login.php" class="phserved-button">
            Continue to Buyer Login
        </a>

    </div>
</div>

<?php include("../includes/footer.php"); ?>
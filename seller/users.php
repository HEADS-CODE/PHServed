<?php

require_once("includes/admin_required.php");
require_once("../config/db_connect.php");

$user_sql =
    "SELECT *
     FROM users
     ORDER BY role, complete_name";

$user_result = mysqli_query($conn, $user_sql);

$page_title = "User Management";
$active_page = "users";

include("includes/header.php");
include("includes/sidebar.php");

?>

<div class="main-wrapper" id="mainWrapper">

    <?php include("includes/top_nav.php"); ?>

    <main class="page-content">

        <?php

        if (isset($_SESSION["user_message"])) {
            $alert_message =
                $_SESSION["user_message"];

            unset($_SESSION["user_message"]);

            ?>

            <script>
                alert(
                    <?php echo json_encode($alert_message); ?>
                );
            </script>

            <?php
        }

        ?>

        <form method="POST" action="delete_users.php" id="bulkDeleteForm"
            onsubmit="return confirm('Delete the selected Buyer account(s)?');">

            <div class="users-heading">
                <div>
                    <h2>Users</h2>

                    <p>
                        Manage Buyer and Administrator accounts.
                    </p>
                </div>

                <div class="users-heading-actions">
                    <button type="submit" class="user-delete-button" id="deleteUsersButton" disabled>
                        Delete
                    </button>

                    <a href="user_form.php" class="phserved-button">
                        Add User
                    </a>
                </div>
            </div>

            <div class="users-table-wrap">

                <table class="users-table">
                    <thead>
                        <tr>
                            <th>
                                <label class="user-select-all">
                                    <input type="checkbox" id="selectAllUsers" aria-label="Select all Buyer accounts">
                                    <span>User</span>
                                </label>
                            </th>
                            <th>Contact</th>
                            <th>Address</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Email Confirmation</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php while (
                            $user =
                            mysqli_fetch_assoc($user_result)
                        ): ?>

                            <?php
                            $is_admin = $user["role"] == "Admin";
                            $role_class = $is_admin
                                ? "role-admin"
                                : "role-buyer";

                            $status_class =
                                $user["account_status"] == "Active"
                                ? "user-status-active"
                                : "user-status-inactive";
                            ?>

                            <tr>
                                <td>
                                    <div class="user-cell">
                                        <input type="checkbox" name="user_ids[]"
                                            value="<?php echo (int) $user["user_id"]; ?>" class="user-row-checkbox"
                                            aria-label="Select <?php echo htmlspecialchars($user["complete_name"]); ?>"
                                            <?php echo $is_admin ? "disabled" : ""; ?>>

                                        <div>
                                            <strong>
                                                <?php echo htmlspecialchars($user["complete_name"]); ?>
                                            </strong>

                                            <small>
                                                <?php echo htmlspecialchars($user["email"]); ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $user["contact_number"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $user["street"] .
                                        ", Barangay " .
                                        $user["barangay"] .
                                        ", " .
                                        $user["city"] .
                                        ", " .
                                        $user["province"] .
                                        " " .
                                        $user["zip_code"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <span class="user-role <?php echo $role_class; ?>">
                                        <?php echo htmlspecialchars(strtoupper($user["role"])); ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="user-status <?php echo $status_class; ?>">
                                        <?php echo htmlspecialchars(strtoupper($user["account_status"])); ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if (
                                        $user["is_confirmed"] == 1
                                    ): ?>

                                        <span class="user-confirmation confirmation-confirmed">CONFIRMED</span>

                                    <?php else: ?>

                                        <span class="user-confirmation confirmation-pending">PENDING</span>

                                    <?php endif; ?>
                                </td>

                                <td>
                                    <a href="user_form.php?id=<?php
                                    echo (int) $user["user_id"];
                                    ?>" class="user-edit-link">
                                        <img src="../images/icons/edit_user.png" alt="" class="action-icon"
                                            onerror="this.style.display='none'">
                                        Edit
                                    </a>
                                </td>
                            </tr>

                        <?php endwhile; ?>

                    </tbody>
                </table>

            </div>

        </form>

    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var selectAll = document.getElementById("selectAllUsers");
            var deleteButton = document.getElementById("deleteUsersButton");
            var buyerCheckboxes = Array.from(
                document.querySelectorAll(".user-row-checkbox:not(:disabled)")
            );

            selectAll.disabled = buyerCheckboxes.length === 0;

            function refreshDeleteState() {
                var selectedCount = buyerCheckboxes.filter(function (checkbox) {
                    return checkbox.checked;
                }).length;

                deleteButton.disabled = selectedCount === 0;
                selectAll.checked =
                    buyerCheckboxes.length > 0 &&
                    selectedCount === buyerCheckboxes.length;
                selectAll.indeterminate =
                    selectedCount > 0 &&
                    selectedCount < buyerCheckboxes.length;
            }

            selectAll.addEventListener("change", function () {
                buyerCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = selectAll.checked;
                });

                refreshDeleteState();
            });

            buyerCheckboxes.forEach(function (checkbox) {
                checkbox.addEventListener("change", refreshDeleteState);
            });

            refreshDeleteState();
        });
    </script>

    <?php include("../includes/footer.php"); ?>
<?php

if (!isset($page_title)) {
    $page_title = "Account";
}

if (!isset($auth_type)) {
    $auth_type = "buyer";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($page_title); ?> | PHServed
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style.css">

    <script src="../assets/js/script.js"></script>
</head>

<body class="auth-page <?php echo htmlspecialchars($auth_type); ?>">

    <header class="auth-brand-header">
        <a href="../buyer/index.php">
            <img src="../images/logo/phserved_logobrand.png" alt="PHServed" class="auth-logo">
        </a>
    </header>

    <div class="auth-main">
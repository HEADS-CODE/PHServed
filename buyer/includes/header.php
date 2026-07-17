<?php

//Default page title
if (!isset($page_title)) {
    $page_title = "Buyer Store";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?php echo htmlspecialchars($page_title); ?> | PHServed
    </title>

<!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

<!-- Google font -->
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&display=swap"
        rel="stylesheet"
    >

<!-- Shared design -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

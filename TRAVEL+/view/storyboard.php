<?php
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Storyboard</title>
        <meta name = "viewport" content = "width=device-width, initial-scale = 1">
        <link rel = "stylesheet" href = "../assets/css/style.css">
        <link rel = "stylesheet" href = "../assets/css/miscellaneous.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    </head>

    <body>
        <header>
            <?php 
                // Check if user is logged in and assign the appropriate navbar
                if (isset($_SESSION['user_id'])) {
                    if ($_SESSION['role'] == 2) {
                        include '/admin/admin-navbar.php';  // For admin users
                    } else {
                        include 'navbar_in.php';   // For normal logged-in users
                    }
                } else {
                    include 'navbar_guest.php';   // For logged-out users
                }
            ?>
        </header>

        <section class="storyboard"></section>

        <footer>
            <?php include 'footer.php'; ?>
        </footer>

    </body>
</html>
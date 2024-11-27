<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>About Project</title>
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
                        include 'admin/admin-navbar.php';  // For admin users
                    } else {
                        include 'navbar_in.php';   // For normal logged-in users
                    }
                } else {
                    include 'navbar_guest.php';   // For logged-out users
                }
            ?>
        </header>

        <section class="about-project">
            <h1> About Our Project </h1>
            <div class = "banner"></div>
            <div class = "about">
                <p>
                    Welcome to Travel+ GH, where we tackle the challenge of bridging the gap between people's 
                    expectations of travel destinations and their actual experiences once they arrive.
                </p>

                <p>
                    Our platform aims to serve both regular travellers and people planning their next vacation or short trips. 
                    We encourage users to share photos and genuine reviews of the places they've visited. 
                    Additionally, we facilitate bookings for trips, hotels, restaurants, and other locations that have garnered reviews.
                </p>

                <p>
                    We also offer an easy-to-navigate and flexible wish list feature, 
                    ensuring your travel planning is both smooth and exciting. 
                    Join us and embark on the journey to plan your next unforgettable trip to Ghana!
                </p>
            </div>
        </section>

        <footer>
            <?php include 'footer.php'; ?>
        </footer>

    </body>
</html>
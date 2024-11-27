<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to the locations page if the user is logged in
    header('Location: view/all-locations.php');
    exit; 
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>TRAVEL+ GH</title>
        <meta name = "viewport" content = "width=device-width, initial-scale = 1">
        <link rel = "stylesheet" href = "assets/css/style.css">
        <link rel = "stylesheet" href = "assets/css/index-style.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">

    </head>

    <body>
        <header>
            <div class="topnav">
                <h1>TRAVEL+ GH</h1>
                <nav class="bar" id="mainNav">
                    <div class="account-container">
                        <button class="account" id="accountBtn">
                            <span class="material-symbols-outlined">account_circle</span>
                        </button>
                        
                        <div class="account-dropdown" id="accountDropdown">
                            <a href="view/login.php">Login</a>
                            <a href="view/signup.php">Sign Up</a>
                        </div>
                    </div>
                    
                    <!-- Navigation Links -->
                    <a href="view/storyboard.php">Storyboard</a>
                    <a href="view/contact-us.php">Contact Us</a>
                    <a href="view/about.php">About The Project</a>
                    <a href="index.php">Home</a>
                </nav>

                <div>
                    <button class="view-more" id="viewMoreBtn">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                </div>
            </div>
        </header>

        <section class = "first">
            <h1>TRAVEL +<br>GHANA</h1>
            <p>Travel Blog and Review Site</p>
        </section>

        <section class = "second">
            <div>
                <h1>Explore The Country</h1>
                <p>Want to get more out of your travelling experience? Get the chance to view the country and learn more about their cultures, people, history and turn yourself into a mini local during your stay!</p>
            </div>
            <button>See Locations</button>
        </section>

        <section class="third">
            <div>
                <h1>Read Honest Reviews</h1>
                <p>Find up-to-date ratings and honest reviews on restaurants, hotels, parks, gardens, and other tour sites across Ghana. Share your own experiences with fellow tourists and backpackers</p>
            </div>
            <button>Discover More</button>
        </section>

        <section class = "fourth">
            <div>
                <h1>Find Nearby Locations</h1>
                <p>Don't want to end the trip early? Want to explore other nearby locations while visiting the tourist site? Or perhaps, you are hungry and want a bite to eat? Maybe even a place to sleep and rest your head for the night? Don't worry we've got you. Check out highly-rated restaurants and hotels nearby</p>
            </div>
            <button>Get Started</button>
        </section>

        <footer>
            <?php include 'view/footer.php'; ?>
        </footer>

        <script src="assets/js/navbar-index.js"></script>
    </body>
</html>


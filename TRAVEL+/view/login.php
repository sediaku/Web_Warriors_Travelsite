<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to the locations page if the user is logged in
    if ($_SESSION['role'] === 'admin'){
        header('Location: /view/admin-dashboard.php');
    }
    header('Location: user-dashboard.php');
    exit; 
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>TRAVEL+ GH</title>
        <meta name = "viewport" content = "width=device-width, initial-scale = 1">
        <link rel = "stylesheet" href = "../assets/css/style.css">
        <link rel = "stylesheet" href = "../assets/css/signup-login-style.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    </head>

    <body>
        <header>
            <?php include 'navbar_guest.php'; ?>
        </header>

        <section class="signup-login">
            <form class="form" method="post" action="../actions/login-action.php">
                <h1>Login</h1>
                
                <div class = "input-field">     
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class = "input-field">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>


                <button type = "submit" class = "button">Log In</button>
                
                <div class="switch-login-signup">
                    <p>Don't have an account? <a href="signup.php"> Sign up here</a></p>
                </div>
            </form>
        </section>

        <footer>
            <?php include 'footer.php'; ?>
        </footer>
        
        <script src="assets/js/login-validation.js"></script>
    </body>
</html>
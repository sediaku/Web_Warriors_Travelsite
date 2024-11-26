<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset="UTF-8">
        <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
        <title>User Dashboard</title>  
        <link rel = "stylesheet" href = "../assets/css/style.css">
        <link rel = "stylesheet" href = "../assets/css/user-dashboard-style.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    </head>

    <body>
        <header>
            <?php include 'navbar_in.php'; ?>
        </header>

        <section class="user-dash">
            <h1>My Blogs</h1>
            <div class = "my-blogs"></div>
            <h1>My Reviews</h1>
            <div class = "my-reviews"></div>
        </section>

        <footer>
            <?php include 'footer.php'; ?>
        </footer>
    </body>
</html>
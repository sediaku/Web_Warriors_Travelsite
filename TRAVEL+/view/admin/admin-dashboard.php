<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset="UTF-8">
        <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
        <title>Location</title>  
        <link rel = "stylesheet" href = "../assets/css/location-style.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    </head>

    <body>
        <header>
            <?php include '../navbar_guest.php'; ?>
        </header>

        <section class="admin-dash">
            <div class = "main">
                <h1>Statistics</h1>
                <div class = "stats">
                    <div class = "users">
                        <h2> Total Number of Users </h2>  
                        <p>Read value</p>                 
                    </div>

                    <div class = "locations">
                        <h2> Total Number of Locations </h2>  
                        <p>Read value</p>                 
                    </div>

                    <div class = "avg-posts">
                        <h2> Average Number of Posts Per Day </h2>  
                        <p>Read value</p>                 
                    </div>

                    <div class = "users">
                        <h2> Total Number of Users </h2>  
                        <p>Read value</p>                 
                    </div>
                </div>

                <h1>My Posts</h1>
                <div class = "posts"></div>
                <h1>My Reviews</h1>
                <div class = "reviews"></div>
            </div>

            <div class = "active-users">
            </div>

        </section>
    </body>
</html>
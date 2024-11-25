<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset="UTF-8">
        <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
        <title>Location</title>  
        <link rel = "stylesheet" href = "../assets/css/style.css">
        <link rel = "stylesheet" href = "../assets/css/location-style.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    </head>

    <body>
        <header>
            <?php include '../navbar_guest.php'; ?>
        </header>

        <section class="location-view">
            <div class = "left">
                <div class = "name">Location Name</div>
                <div class = "book"><a href="">Book Now</a></div>
                <div class = "rating">Average Rating:<span>4.00</span></div>
                <button class = "wishlist">Add to Wishlist</button>
                <button class = "wishlist">See Blog Posts Mentioning This Location</button>
            </div>

            <div class = "right">
                <div class = "Description">
                    "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, 
                    eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. 
                    Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                    Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit"
                </div>
                <div class = "review-section">
                    <h1>Reviews</h1>
                    <div class = "reviews"></div>
                    <button>Add Review</button>
                </div>
            </div>

        </section>
    </body>
</html>
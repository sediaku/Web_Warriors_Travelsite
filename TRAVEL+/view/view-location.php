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
        <?php include 'navbar_in.php'; ?>
    </header>

        <section class="location-view">
            <div class = "left">
                <div class = "name">Location Name</div>
                <div class = "book"><a href="">Book Now</a></div>
                <div class = "rating">Average Rating:<span>4.00</span></div>
                <button name ="add-to-wishlist" class = "wishlist">Add to Wishlist</button>
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
                    <button class="add-review-btn">Add Review</button>
                </div>
            </div>
        </section>
        <div id="reviewModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal">&times;</span>
                    <h2>Write a Review!</h2>
                    <form class="review-form" id="reviewForm">
                        <div>
                            <label>Rating</label>
                            <div class="star-rating" id="starRating">
                                <span class="star" data-rating="1">&#9734</span>
                                <span class="star" data-rating="2">&#9734</span>
                                <span class="star" data-rating="3">&#9734</span>
                                <span class="star" data-rating="4">&#9734</span>
                                <span class="star" data-rating="5">&#9734</span>
                            </div>
                        </div>
                        <div>
                            <label for="reviewText">Your Review</label><br>
                            <textarea id="reviewText" placeholder="Share Your Experience..." required></textarea>
                        </div>
                        <div class="modal-buttons">
                            <button type="button" class="cancel-review">Cancel</button>
                            <button type="submit" class="submit-review" disabled>Submit Review</button>
                        </div>
                    </form>
                </div>
        </div>
        <script src="../assets/js/review.js"></script>
    </body>
</html>
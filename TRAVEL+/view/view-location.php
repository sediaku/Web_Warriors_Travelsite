<?php
// Include the PHP file that fetches the location data and handles reviews
include '../functions/locationdetails.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($locationDetails['location_name']); ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/location-style.css">
</head>
<body>
<header>
    <?php include 'navbar_in.php'; ?>
</header>

<section class="location-view">
    <div class="left">
        <div class="name"><?php echo htmlspecialchars($locationDetails['location_name']); ?></div>
        <div class="book">
            <a href="<?php echo $locationDetails['booking_link']; ?>">Book Now</a>
        </div>
        <div class="rating">
            Average Rating: <span><?php echo htmlspecialchars($locationDetails['average_rating'] ?? 'N/A'); ?></span>
        </div>
        <button name="add-to-wishlist" class="wishlist">Add to Wishlist</button>
        <button class="blog">See Blog Posts Mentioning This Location</button>
    </div>

    <div class="right">
        <div class="Description">
            <?php echo htmlspecialchars($locationDetails['description']); ?>
        </div>

        <div class="review-section">
            <h1>Reviews</h1>
            <div class="reviews"></div>
            <button class="add-review-btn">Add Review</button>
        </div>

            
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
        </div>
</section>

<script src="../assets/js/review.js"></script>
<script>document.getElementById('addToWishlistBtn').addEventListener('click', function() {
    
    var locationId = <?php echo json_encode($locationDetails['location_id']); ?>;

    
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../functions/addtowishlist.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
    // Handle the response
    xhr.onload = function() {
        if (xhr.status == 200) {
            alert(xhr.responseText); 
            
        }
    };
    
    
    xhr.send("location_id=" + locationId);
});
</script>
</body>
</html>

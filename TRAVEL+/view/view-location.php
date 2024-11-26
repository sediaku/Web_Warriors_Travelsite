<?php
// Include the PHP file that fetches the location data and handles reviews
session_start();
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
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
    <body>
    <header>
        <?php 
        // Check if user is logged in and assign the appropriate navbar
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role'] === 'admin') {
                include '/admin/admin-navbar.php';  // For admin users
            } else {
                include 'navbar_in.php';   // For normal logged-in users
            }
        } else {
            include 'navbar_guest.php';   // For logged-out users
        }
    ?>
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
                <button name="add-to-wishlist" class="wishlist" id="addToWishlistBtn">Add to Wishlist</button>
                <button class="blog">See Blog Posts Mentioning This Location</button>
            </div>

            <div class="right">
                <div class="Description">
                    <?php echo htmlspecialchars($locationDetails['description']); ?>
                </div>

                <div class="review-section">
                    <h1>Reviews</h1>
                    <div class="rating">
                        Average Rating: <span><?php 
                            echo isset($locationDetails['average_rating']) && $locationDetails['average_rating'] !== null 
                                ? number_format($locationDetails['average_rating'], 1) 
                                : 'N/A'; 
                        ?></span>
                    </div>
                    <div class="reviews" id="reviewsContainer">
                        <?php if (empty($locationDetails['reviews'])): ?>
                            <div class="no-reviews">
                                <p>No reviews yet. Be the first to review!</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($locationDetails['reviews'] as $review): ?>
                                <div class="review-item" data-review-id="<?php echo $review['review_id']; ?>">
                                    <div class="review-header">
                                        <img src="<?php 
                                            echo htmlspecialchars(
                                                $review['profile_picture'] 
                                                ? $review['profile_picture'] 
                                                : '../assets/images/default-profile.png'
                                            ); 
                                        ?>" alt="Profile" class="reviewer-profile">
                                        <div class="reviewer-info">
                                            <span class="reviewer-name">
                                                <?php echo htmlspecialchars($review['username']); ?>
                                            </span>
                                            <div class="review-rating">
                                                <?php 
                                                $rating = $review['rating'];
                                                for ($i = 1; $i <= 5; $i++) {
                                                    echo $i <= $rating ? '&#9733;' : '&#9734;';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <span class="review-date">
                                            <?php echo date('F j, Y', strtotime($review['review_date'])); ?>
                                        </span>
                                    </div>
                                    <div class="review-content">
                                        <?php echo htmlspecialchars($review['review_text']); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button class="add-review-btn" data-location-id="<?php echo $locationDetails['location_id']; ?>">Add Review</button>
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
        <script src="../assets/js/navbar-in.js"></script>
        <script>document.getElementById('addToWishlistBtn').addEventListener('click', function() {
            
            var locationId = <?php echo json_encode($locationDetails['location_id']); ?>;
            
            if (!locationId) {
                alert("Location ID is not available.");
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../functions/addtowishlist.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

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

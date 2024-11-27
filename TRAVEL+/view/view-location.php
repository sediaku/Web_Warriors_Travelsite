<?php
// Include the PHP file that fetches the location data and handles reviews
session_start();
include '../functions/locationdetails.php'; 

// Check if a message is present in the query string
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : null;
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

    <main>
        <!-- Display message if available -->
        <?php if ($message): ?>
            <div class="message">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <section class="location-view">
            <div class="left">
                <div class="name"><?php echo htmlspecialchars($locationDetails['location_name']); ?></div>
                <div class="book">
                    <a href="<?php echo htmlspecialchars($locationDetails['booking_link']); ?>">Book Now</a>
                </div>
                <div class="rating">
                    Average Rating: <span><?php echo htmlspecialchars($locationDetails['average_rating'] ?? 'N/A'); ?></span>
                </div>
                <!-- Add to Wishlist Form -->
                <form action="../functions/addtowishlist.php" method="POST" class="wishlist-form">
                    <input type="hidden" name="location_id" value="<?php echo htmlspecialchars($locationDetails['location_id']); ?>">
                    <button type="submit" name="add-to-wishlist" class="wishlist">Add to Wishlist</button>
                </form>
            </div>

            <div class="right">
                <div class="Description">
                    <?php echo htmlspecialchars($locationDetails['description']); ?>
                </div>

                <div class="review-section">
                    <h1>Reviews</h1>
                    
                    <div class="reviews" id="reviewsContainer">
                        <?php if (empty($locationDetails['reviews'])): ?>
                            <div class="no-reviews">
                                <p>No reviews yet. Be the first to review!</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($locationDetails['reviews'] as $review): ?>
                                <div class="review-item" data-review-id="<?php echo $review['review_id']; ?>">      
                                    <span class="reviewer-name">
                                        <?php echo htmlspecialchars($review['username']); ?>
                                    </span>
                                    <span class="review-rating">
                                        <?php 
                                        $rating = $review['rating'];
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo $i <= $rating ? '&#9733;' : '&#9734;';
                                        }
                                        ?>
                                    </span>
                                    <p class="review-date">
                                        <?php echo date('F j, Y', strtotime($review['review_date'])); ?>
                                    </p>
                                      
                                    <p class="review-content">
                                        <?php echo htmlspecialchars($review['review_text']); ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button class="add-review-btn" data-location-id="<?php echo $locationDetails['location_id']; ?>">Add Review</button>
                </div>       
            </div>
        </section>

        <!-- Modal for Adding Reviews -->
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
    </main>

    <script src="../assets/js/review.js"></script>
</body>
</html>

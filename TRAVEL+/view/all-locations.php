<?php

session_start(); 
include '../db/db-config.php';

try {
    $dbConnection = getDatabaseConnection();
    $query = "SELECT location_id, location_name, description FROM locations";
    $stmt = $dbConnection->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $locations = [];
    while ($row = $result->fetch_assoc()) {
        $locations[] = $row;
    }

    $stmt->close();
    $dbConnection->close();
} catch (Exception $e) {
    die("Error fetching locations: " . htmlspecialchars($e->getMessage()));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Locations</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/all-locations-style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
<body>
<header>
    <?php 
        // Check if user is logged in and assign the appropriate navbar
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role'] == 2) {
                include '../view/admin/admin-navbar.php';  // For admin users
            } else {
                include 'navbar_in.php';   // For normal logged-in users
            }
        } else {
            include 'navbar_guest.php';   // For logged-out users
        }
    ?>
</header>

<section class="all-locations">
    <h1>Explore All Locations</h1>

    <!-- Search Bar -->
    <div class="search-container">
        <input 
            type="text" 
            id="search-bar" 
            class="search-bar" 
            placeholder="Search locations..."
            onkeyup="searchLocations()"
        >
    </div>

    <!-- Results Container -->
    <div id="search-results" class="locations-container">
        <?php if (!empty($locations)): ?>
            <?php foreach ($locations as $location): ?>
                <div class="location-card">
                    <img src ="../assets/images/default-image.png" class="brief-view">
                    <div class="location-header">
                        <h2><?php echo htmlspecialchars($location['location_name']); ?></h2>
                    </div>
                    <div class="location-description">
                        <p><?php echo htmlspecialchars(substr($location['description'], 0, 100)); ?>...</p>
                    </div>
                    <div class="location-actions">
                        <a href="view-location.php?location_id=<?php echo $location['location_id']; ?>" class="details-btn">View Details</a>
                        <button class="wishlist-btn" data-location-id="<?php echo $location['location_id']; ?>">Add to Wishlist</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No locations available at the moment.</p>
        <?php endif; ?>
    </div>
</section>

<script>
    // Function to search locations dynamically
    function searchLocations() {
        const query = document.getElementById('search-bar').value;

        // Use AJAX to send search query to the backend
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "../functions/search-locations.php?q=" + encodeURIComponent(query), true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                // Update the results container with the response
                document.getElementById('search-results').innerHTML = xhr.responseText;
            }
        };

        xhr.send();
    }
</script>

<footer>
    <?php include 'footer.php'; ?>
</footer>
</body>
</html>

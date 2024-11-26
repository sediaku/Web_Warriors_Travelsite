<?php

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
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/location-style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
<body>
<header>
    <?php include 'navbar_in.php'; ?>
</header>

    <section class="all-locations">
        <h1>Explore All Locations</h1>
        <div class="locations-container">
            <?php if (!empty($locations)): ?>
                <?php foreach ($locations as $location): ?>
                    <div class="location-card">
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

    <script src="../assets/js/navbar-in.js"></script>
    <script>
        // Add location to wishlist functionality
        document.querySelectorAll('.wishlist-btn').forEach(button => {
            button.addEventListener('click', function() {
                const locationId = this.getAttribute('data-location-id');

                if (!locationId) {
                    alert("Location ID is not available.");
                    return;
                }

                const xhr = new XMLHttpRequest();
                xhr.open("POST", "../functions/addtowishlist.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        alert(xhr.responseText); // Response from server
                    }
                };

                xhr.send("location_id=" + locationId); // Send location ID
            });
        });
    </script>
    <br>
    <h3>Would you like to add a new location? <a href="add-location.php">Create Location</a></h3>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>
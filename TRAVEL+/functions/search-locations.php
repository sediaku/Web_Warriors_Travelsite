<?php

include '../db/db-config.php';

// Get search query
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

try {
    $dbConnection = getDatabaseConnection();

    // Fetch matching locations
    $sql = "SELECT location_id, location_name, description 
            FROM locations 
            WHERE location_name LIKE ? OR description LIKE ?";
    $stmt = $dbConnection->prepare($sql);
    $searchTerm = '%' . $query . '%';
    $stmt->bind_param('ss', $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
                <div class='location-card'>
                    <img src='../assets/images/default-image.png' class='brief-view'>
                    <div class='location-header'>
                        <h2>" . htmlspecialchars($row['location_name']) . "</h2>
                    </div>
                    <div class='location-description'>
                        <p>" . htmlspecialchars(substr($row['description'], 0, 100)) . "...</p>
                    </div>
                    <div class='location-actions'>
                        <a href='view-location.php?location_id=" . $row['location_id'] . "' class='details-btn'>View Details</a>
                        <button class='wishlist-btn' data-location-id='" . $row['location_id'] . "'>Add to Wishlist</button>
                    </div>
                </div>
            ";
        }
    } else {
        echo "<p>No locations match your search query.</p>";
    }

    $stmt->close();
    $dbConnection->close();
} catch (Exception $e) {
    echo "<p>Error fetching search results: " . htmlspecialchars($e->getMessage()) . "</p>";
}

?>

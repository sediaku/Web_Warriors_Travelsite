<?php
// Include your database connection file
include '../db/db-config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add to your wishlist.");
}

// Validate the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the location_id from the POST request
    $locationId = $_POST['location_id'] ?? null;
    $userId = $_SESSION['user_id']; // Assuming user ID is stored in session

    // Validate the location_id
    if (!$locationId || !is_numeric($locationId)) {
        die("Invalid location ID.");
    }

    $dbConnection = getDatabaseConnection();

    // Check if the location is already in the user's wishlist
    $checkQuery = "
        SELECT * FROM wishlist
        WHERE user_id = ? AND location_id = ?
    ";
    $stmt = $dbConnection->prepare($checkQuery);
    $stmt->bind_param("ii", $userId, $locationId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Location is already in the wishlist
        $message = "Location is already in your wishlist.";
    } else {
        // Insert the location into the wishlist
        $insertQuery = "
            INSERT INTO wishlist (user_id, location_id)
            VALUES (?, ?)
        ";
        $stmt = $dbConnection->prepare($insertQuery);
        $stmt->bind_param("ii", $userId, $locationId);
        if ($stmt->execute()) {
            $message = "Location added to your wishlist!";
        } else {
            $message = "Failed to add location to your wishlist.";
        }
    }

    $stmt->close();
    $dbConnection->close();

    // Redirect back to the location view page with a success or error message
    header("Location: ../view/view-location.php?location_id=" . urlencode($locationId) . "&message=" . urlencode($message));
    exit;
} else {
    // Invalid request method
    die("Invalid request method.");
}
?>

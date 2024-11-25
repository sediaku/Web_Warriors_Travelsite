<?php

require_once 'db.config.php';


session_start();


if (!isset($_SESSION['user_id'])) {
    die("Access denied: You must be logged in to perform this action.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add-to-wishlist'])) {
    $userId = $_SESSION['user_id']; 
    $locationId = $_POST['location_id']; 
    
    $checkQuery = "SELECT * FROM wishlist WHERE user_id = ? AND location_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $userId, $locationId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Location is already in your wishlist.";
        exit;
    }

    // Add the location to the wishlist
    $insertQuery = "INSERT INTO wishlist (user_id, location_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ii", $userId, $locationId);

    if ($stmt->execute()) {
        echo "Location added to wishlist successfully.";
    } else {
        echo "Failed to add location to wishlist: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();

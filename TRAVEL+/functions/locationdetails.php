<?php

include '../db/db-config.php';

function getLocationReviews($locationId, $dbConnection) {
    $reviewQuery = "
        SELECT r.review_id, r.rating, r.review_text, r.review_date, 
               u.username, u.profile_picture
        FROM reviews r
        JOIN users u ON r.user_id = u.user_id
        WHERE r.location_id = ?
        ORDER BY r.review_date DESC
    ";
    $stmt = $dbConnection->prepare($reviewQuery);
    $stmt->bind_param("i", $locationId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $reviews = [];
    
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
    
    $stmt->close();
    
    return $reviews;
}

function getLocationAverageRating($locationId, $dbConnection) {
    $avgQuery = "
        SELECT AVG(rating) as average_rating 
        FROM reviews 
        WHERE location_id = ?
    ";
    $stmt = $dbConnection->prepare($avgQuery);
    $stmt->bind_param("i", $locationId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $stmt->close();
    
    return $row['average_rating'] !== null ? round($row['average_rating'], 1) : null;
}

$dbConnection = getDatabaseConnection();

$locationId = $_GET['location_id'] ?? null;

if (!$locationId || !is_numeric($locationId)) {
    die("Invalid location ID.");
}

$locationQuery = "
    SELECT location_id, location_name, booking_link, description
    FROM locations
    WHERE location_id = ?
";
$stmt = $dbConnection->prepare($locationQuery);
$stmt->bind_param("i", $locationId);
$stmt->execute();
$locationResult = $stmt->get_result();

if ($locationResult->num_rows === 1) {
    // Fetch location details
    $locationDetails = $locationResult->fetch_assoc();
    
    // Fetch reviews
    $locationDetails['reviews'] = getLocationReviews($locationId, $dbConnection);
    
    // Fetch and add average rating
    $locationDetails['average_rating'] = getLocationAverageRating($locationId, $dbConnection);
} else {
    die("<p>Location not found.</p>");
}

$stmt->close();
$dbConnection->close();
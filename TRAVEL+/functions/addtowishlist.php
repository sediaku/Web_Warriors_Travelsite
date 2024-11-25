<?php

require_once 'db-config.php';


session_start();


if (!isset($_SESSION['user_id'])) {
    http_response_code(403); 
    echo "Access denied: You must be logged in to perform this action.";
    exit;
}


$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (isset($data['location_name'])) {
    $userId = $_SESSION['user_id']; 
    $locationName = $data['location_name'];

    
    $locationQuery = "SELECT location_id FROM locations WHERE location_name = ?";
    $stmt = $conn->prepare($locationQuery);
    $stmt->bind_param("s", $locationName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Location not found.";
        exit;
    }

    $locationRow = $result->fetch_assoc();
    $locationId = $locationRow['location_id'];

 
    $checkQuery = "SELECT * FROM wishlist WHERE user_id = ? AND location_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $userId, $locationId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Location is already in your wishlist.";
        exit;
    }

    
    $insertQuery = "INSERT INTO wishlist (user_id, location_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ii", $userId, $locationId);

    if ($stmt->execute()) {
        echo "Location added to wishlist successfully.";
    } else {
        echo "Failed to add location to wishlist: " . $stmt->error;
    }

    $stmt->close();
} else {
    http_response_code(400); 
    echo "Invalid request.";
}


$conn->close();


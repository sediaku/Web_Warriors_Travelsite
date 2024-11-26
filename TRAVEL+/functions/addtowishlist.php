<?php
include '../db/db-config.php';
session_start();


if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add to your wishlist.");
}


$locationId = $_POST['location_id'] ?? null;
$userId = $_SESSION['user_id']; 

// Validate location_id
if (!$locationId || !is_numeric($locationId)) {
    die("Invalid location ID.");
}


$dbConnection = getDatabaseConnection();

$checkQuery = "
    SELECT * FROM wishlist
    WHERE user_id = ? AND location_id = ?
";
$stmt = $dbConnection->prepare($checkQuery);
$stmt->bind_param("ii", $userId, $locationId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
   
    echo "This location is already in your wishlist.";
} else {
    
    $insertQuery = "
        INSERT INTO wishlist (user_id, location_id)
        VALUES (?, ?)
    ";
    $stmt = $dbConnection->prepare($insertQuery);
    $stmt->bind_param("ii", $userId, $locationId);
    $stmt->execute();
    
    
    echo "Location added to your wishlist!";
}

$stmt->close();
$dbConnection->close();
?>

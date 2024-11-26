<?php

include '../db/db-config.php'; 
$dbConnection = getDatabaseConnection();


$locationId = $_GET['location_id'] ?? null;


if (!$locationId || !is_numeric($locationId)) {
    die("Invalid location ID.");
}


$locationQuery = "
    SELECT location_id,location_name, booking_link, description
    FROM locations
    WHERE location_id = ?
";
$stmt = $dbConnection->prepare($locationQuery);
$stmt->bind_param("i", $locationId);
$stmt->execute();
$locationResult = $stmt->get_result();

if ($locationResult->num_rows === 1) {
    $locationDetails = $locationResult->fetch_assoc();
} else {
    die("<p>Location not found.</p>");
}
$stmt->close();

$dbConnection->close();


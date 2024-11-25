<?php

require_once 'db.config.php';


session_start();


if (!isset($_SESSION['user_id'])) {
    http_response_code(403); 
    echo "Access denied: You must be logged in.";
    exit;
}


$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (isset($data['location_id'])) {
    $userId = $_SESSION['user_id'];
    $locationId = $data['location_id'];

    
    $deleteQuery = "DELETE FROM wishlist WHERE user_id = ? AND location_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("ii", $userId, $locationId);

    if ($stmt->execute()) {
        echo "Location removed from wishlist.";
    } else {
        echo "Failed to remove location: " . $stmt->error;
    }

    $stmt->close();
} else {
    http_response_code(400); 
    echo "Invalid request.";
}

$conn->close();
?>

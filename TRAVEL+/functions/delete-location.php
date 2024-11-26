<?php
include '../db/db-config.php';
session_start();

// Check if user is logged in and has admin privileges (role = 2)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    die("Access denied. You do not have permission to view this page.");
}

$dbConnection = getDatabaseConnection();

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $locationId = isset($_POST['location_id']) ? intval($_POST['location_id']) : 0;

    if ($locationId <= 0) {
        die("Invalid location ID.");
    }

    $deleteQuery = "DELETE FROM locations WHERE location_id = ?";
    $stmt = $dbConnection->prepare($deleteQuery);
    $stmt->bind_param("i", $locationId);

    if ($stmt->execute()) {
        echo "Location deleted successfully!";
    } else {
        echo "Failed to delete location.";
    }

    $stmt->close();
    $dbConnection->close();
    header("Location: ../view/admin/location-management.php"); 
    exit;
} else {
    die("Invalid request method.");
}
?>

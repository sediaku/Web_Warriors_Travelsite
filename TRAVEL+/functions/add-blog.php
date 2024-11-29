<?php
include '../db/db-config.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add a blog.");
}

$userId = $_SESSION['user_id'];
// Get the user's role from the session
$userRole = $_SESSION['role'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input
    $blogTitle = trim($_POST['blog_title']);
    $blogContent = trim($_POST['blog_content']);
    $locationName = trim($_POST['location_name']);
    $newLocation = trim($_POST['new_location']); // Custom location input

    // Check for required fields
    if (empty($blogTitle) || empty($blogContent) || (empty($locationName) && empty($newLocation))) {
        die("All fields are required.");
    }

    // Handle location
    $dbConnection = getDatabaseConnection();

    if (!empty($newLocation)) {
        // Check if the new location already exists
        $locationCheckQuery = "SELECT location_id FROM locations WHERE location_name = ?";
        $locationCheckStmt = $dbConnection->prepare($locationCheckQuery);
        $locationCheckStmt->bind_param("s", $newLocation);
        $locationCheckStmt->execute();
        $locationCheckResult = $locationCheckStmt->get_result();

        // If the location doesn't exist, insert it
        if ($locationCheckResult->num_rows === 0) {
            $insertLocationQuery = "INSERT INTO locations (location_name) VALUES (?)";
            $insertLocationStmt = $dbConnection->prepare($insertLocationQuery);
            $insertLocationStmt->bind_param("s", $newLocation);
            if ($insertLocationStmt->execute()) {
                $locationId = $dbConnection->insert_id; // Get the last inserted location_id
            } else {
                die("Error adding new location.");
            }
            $insertLocationStmt->close();
        } else {
            $locationRow = $locationCheckResult->fetch_assoc();
            $locationId = $locationRow['location_id'];
        }

        $locationCheckStmt->close();
    } else {
        // Use the selected location
        $locationQuery = "SELECT location_id FROM locations WHERE location_name = ?";
        $locationStmt = $dbConnection->prepare($locationQuery);
        $locationStmt->bind_param("s", $locationName);
        $locationStmt->execute();
        $locationResult = $locationStmt->get_result();

        if ($locationResult->num_rows === 0) {
            die("Invalid location selected.");
        }

        $locationRow = $locationResult->fetch_assoc();
        $locationId = $locationRow['location_id'];
        $locationStmt->close();
    }

    // Insert blog into the database
    $blogQuery = "
        INSERT INTO blog (user_id, title, content, location_id, published_date) 
        VALUES (?, ?, ?, ?, NOW())";
    $blogStmt = $dbConnection->prepare($blogQuery);
    $blogStmt->bind_param("issi", $userId, $blogTitle, $blogContent, $locationId);

    if ($blogStmt->execute()) {
        echo "Blog added successfully.";

        // Redirect based on user role
        if ($userRole == 1) {
            header("Location: ../view/user-dashboard.php");
        } elseif ($userRole == 2) {
            header("Location: ../view/admin/admin-dashboard.php");
        }

        exit;
    } else {
        echo "Error adding blog: " . $dbConnection->error;
    }

    $blogStmt->close();
    $dbConnection->close();
}
?>


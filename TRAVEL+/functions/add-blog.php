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
    $locationId = trim($_POST['location_id']); // Changed to location_id from the select input

    // Check for required fields
    if (empty($blogTitle) || empty($blogContent) || empty($locationId)) {
        die("All fields are required.");
    }

    // Get database connection
    $dbConnection = getDatabaseConnection();

    // Verify location exists (optional, but recommended)
    $locationQuery = "SELECT location_id FROM locations WHERE location_id = ?";
    $locationStmt = $dbConnection->prepare($locationQuery);
    $locationStmt->bind_param("i", $locationId);
    $locationStmt->execute();
    $locationResult = $locationStmt->get_result();

    // Check if location exists
    if ($locationResult->num_rows === 0) {
        die("Invalid location selected.");
    }
    $locationStmt->close();

    // Insert blog into the database
    $blogQuery = "
        INSERT INTO blog (user_id, title, content, location_id, published_date) 
        VALUES (?, ?, ?, ?, NOW())";
    $blogStmt = $dbConnection->prepare($blogQuery);
    $blogStmt->bind_param("issi", $userId, $blogTitle, $blogContent, $locationId);

    if ($blogStmt->execute()) {
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
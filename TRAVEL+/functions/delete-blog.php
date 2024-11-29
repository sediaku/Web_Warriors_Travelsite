<?php
include '../db/db-config.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to delete a blog.");
}

$userId = $_SESSION['user_id'];
// Get the user's role from the session
$userRole = $_SESSION['role'];

// Ensure the blog_id is passed in the POST request
if (isset($_POST['blog_id'])) {
    $blogId = (int) $_POST['blog_id'];

    // Prepare and execute the delete query
    $dbConnection = getDatabaseConnection();
    $deleteQuery = "DELETE FROM blog WHERE blog_id = ? AND user_id = ?";
    $deleteStmt = $dbConnection->prepare($deleteQuery);
    $deleteStmt->bind_param("ii", $blogId, $userId);

    if ($deleteStmt->execute()) {
        // Redirect based on user role after successful deletion
        if ($userRole == 1) {
            header("Location: ../view/user-dashboard.php");
        } elseif ($userRole == 2) {
            header("Location: ../view/admin/admin-dashboard.php");
        }
        exit;
    } else {
        die("Error deleting blog: " . $dbConnection->error);
    }

    $deleteStmt->close();
    $dbConnection->close();
} else {
    die("Blog ID not provided.");
}

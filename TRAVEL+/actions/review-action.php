<?php
session_start();
require_once '../db/db-config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Function to send error response
function sendErrorResponse($message, $code = 400) {
    ob_clean(); 
    http_response_code($code);
    echo json_encode([
        'success' => false, 
        'error' => $message
    ]);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])){
    sendErrorResponse('User not logged in', 401);
}

// Get raw POST data
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

// Validate input
if (!$data) {
    sendErrorResponse('Invalid JSON input');
}

// Check required fields
if (!isset($data['location_id']) || !isset($data['rating']) || !isset($data['text'])){
    sendErrorResponse('Missing required fields');
}

// Sanitize and validate inputs
$user_id = $_SESSION['user_id'];
$location_id = filter_var($data['location_id'], FILTER_VALIDATE_INT);
$rating = filter_var($data['rating'], FILTER_VALIDATE_INT);
$review_text = trim($data['text']);

// Additional validation
if ($location_id === false || $rating === false){
    sendErrorResponse('Invalid location or rating');
}

if ($rating < 1 || $rating > 5){
    sendErrorResponse('Rating must be between 1 and 5');
}

if (empty($review_text)){
    sendErrorResponse('Review text cannot be empty');
}

// Get database connection
$conn = getDatabaseConnection();

try {
    // Start transaction
    $conn->begin_transaction();

    // Prepare and execute review insertion
    $stmt = $conn->prepare("INSERT INTO reviews (user_id, location_id, rating, review_text) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $user_id, $location_id, $rating, $review_text);
    $stmt->execute();

    // Get the ID of the newly inserted review
    $new_review_id = $stmt->insert_id;

    // Check for insertion error
    if ($stmt->error){
        throw new Exception("Review insertion failed: ". $stmt->error);
    }
    $stmt->close();

    // Calculate new average rating
    $avg_stmt = $conn->prepare("
        SELECT AVG(rating) as new_average 
        FROM reviews 
        WHERE location_id = ?
    ");
    $avg_stmt->bind_param("i", $location_id);
    $avg_stmt->execute();
    $avg_result = $avg_stmt->get_result();
    $avg_row = $avg_result->fetch_assoc();
    $new_average_rating = $avg_row['new_average'];
    $avg_stmt->close();

    // Update locations table with new average rating
    $update_stmt = $conn->prepare("
        UPDATE locations 
        SET average_rating = ? 
        WHERE location_id = ?
    ");
    $update_stmt->bind_param("di", $new_average_rating, $location_id);
    $update_stmt->execute();
    $update_stmt->close();

    // Commit transaction
    $conn->commit();

    // Send success response with new review details
    echo json_encode([
        'success' => true, 
        'message' => 'Review submitted successfully',
        'review_id' => $new_review_id,
        'username' => $_SESSION['username'], 
        'new_average_rating' => (float)$new_average_rating
    ]);
}
catch (Exception $e){
    // Rollback transaction on error
    $conn->rollBack();

    // Send error response
    sendErrorResponse('Database error: ' . $e->getMessage(), 500);
}
finally {
    // Close database connection
    $conn->close();
}
?>
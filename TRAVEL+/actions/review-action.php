<?php
session_start();
require_once '../db/db-config.php';

error_reporting(E_ALL);
ini_set('display_errors',1);

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])){
    http_response_code(401);
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['location_id']) || !isset($data['rating']) || !isset($data['text'])){
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$user_id = $_SESSION['user_id'];
$location_id = intval($data['location_id']);
$rating = intval($data['rating']);
$review_text = $data['text'];

if ($rating < 1 || $rating > 5){
    http_response_code(400);
    echo json_encode(['error' => 'Invalid rating']);
    exit;
}

$conn = getDatabaseConnection();

try{
    $conn->begin_transaction();

    $stmt = $conn->prepare("INSERT INTO reviews (user_id, location_id, rating, review_text) VALUES (?,?,?,?)");
    $stmt->bind_param("iiss", $user_id, $location_id, $rating, $review_text);
    $stmt->execute();

    if ($stmt->error){
        throw new Exception("Review insertion failed: ". $stmt->error);
    }
    $stmt->close();

    $avg_stmt = $conn->prepare("
        UPDATE locations
        SET average_rating = (
            SELECT AVG(rating)
            FROM reviews
            WHERE location_id = ?
        )
        WHERE location_id = ?
    ");
    $avg_stmt->bind_param("ii", $location_id, $location_id);
    $avg_stmt->execute();

    if ($avg_stmt->error){
        throw new Exception("Average rating update failed: ".$avg_stmt->error);
    }
    $avg_stmt->close();

    $conn->commit();

    echo json_encode(['success' => true, 'message' => 'Review submitted successfully']);
}
catch (Exception $e){
    $conn->rollBack();

    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
finally{
    $conn->close();
}
?>
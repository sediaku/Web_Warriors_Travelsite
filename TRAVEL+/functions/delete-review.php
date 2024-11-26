<?php
include '../db/db-config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token'], $_SESSION['review_tokens'][$_POST['token']])) {
    $review = $_SESSION['review_tokens'][$_POST['token']];
    unset($_SESSION['review_tokens'][$_POST['token']]); 

    $dbConnection = getDatabaseConnection();
    $deleteQuery = "DELETE FROM reviews WHERE review_id = ?";
    $deleteStmt = $dbConnection->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $review['review_id']);
    $deleteStmt->execute();
    $deleteStmt->close();
    $dbConnection->close();

    
} else {
    die("Invalid request or token.");
}

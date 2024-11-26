<?php
include '../db/db-config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token'], $_SESSION['blog_tokens'][$_POST['token']])) {
    $blog = $_SESSION['blog_tokens'][$_POST['token']];
    unset($_SESSION['blog_tokens'][$_POST['token']]);

    $dbConnection = getDatabaseConnection();
    $deleteQuery = "DELETE FROM blog WHERE blog_id = ?";
    $deleteStmt = $dbConnection->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $blog['blog_id']);
    $deleteStmt->execute();
    $deleteStmt->close();
    $dbConnection->close();

    
} else {
    die("Invalid request or token.");
}


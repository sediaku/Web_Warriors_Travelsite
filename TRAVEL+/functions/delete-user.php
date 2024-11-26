<?php
session_start();
include '../db/db-config.php';

// Check if the user is logged in and has admin privileges (role = 2)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    die("Access denied. You do not have permission to perform this action.");
}

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the user ID from the form submission
    if (isset($_POST['user_id']) && is_numeric($_POST['user_id'])) {
        $userIdToDelete = intval($_POST['user_id']); // Sanitize input

        // Get database connection
        $dbConnection = getDatabaseConnection();

        // Check if the user exists
        $checkQuery = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $dbConnection->prepare($checkQuery);
        $stmt->bind_param("i", $userIdToDelete);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // User exists, proceed to delete
            $deleteQuery = "DELETE FROM users WHERE user_id = ?";
            $deleteStmt = $dbConnection->prepare($deleteQuery);
            $deleteStmt->bind_param("i", $userIdToDelete);

            if ($deleteStmt->execute()) {
                // Redirect with a success message
                header("Location: ../view/admin/user-management.php?message=User+deleted+successfully");
            } else {
                // Handle errors
                header("Location: ../view/admin/user-management.php?error=Failed+to+delete+user");
            }

            $deleteStmt->close();
        } else {
            // User does not exist
            header("Location: ../view/admin/user-management.php?error=User+not+found");
        }

        $stmt->close();
        $dbConnection->close();
    } else {
        // Invalid user ID
        header("Location: ../view/admin/user-management.php?error=Invalid+user+ID");
    }
} else {
    // Invalid request method
    header("Location: ../view/adminuser-management.php?error=Invalid+request+method");
}
exit;

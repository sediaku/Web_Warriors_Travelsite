<?php

include '../db/db-config.php'; 
$dbConnection = getDatabaseConnection();

$username = "";
$password = "";

$username_error = "";
$password_error = "";

$error = false;
$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username)){
        $username_error = "Username is required";
        $error = true;
    } 
    if (empty($password)) {
        $password_error = "Password is required";
        $error = true;
    }

    if(!$error){
        // Query to check the username and fetch user details
        $query = "SELECT user_id, username, password, role FROM users WHERE username = ?";
        $stmt = $dbConnection->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on user role
                if ($user['role'] == 1) {
                    header("Location: ../view/user-dashboard.php");
                    exit;
                }else{
                    header("Location: ../view/admin/admin-dashboard.php");
                    exit;

                }
                
            } else {
                $password_error = "Invalid password";
                $error = true;
            }
        } else {
            $password_error = "Invalid username or password.";
        }
    }


    $stmt->close();
}
?>
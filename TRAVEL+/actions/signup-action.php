<?php
include ("../db/db-config.php");
$dbConnection = getDatabaseConnection();

session_start();



$username = "";
$email = "";

$username_error = "";
$email_error = "";
$password_error = "";
$confirm_password_error = "";

$error = false;

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username)){
        $username_error = "User name is required";
        $error = true;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $email_error = "Email format is not valid";
        $error = true;
    }

    


    $statement = $dbConnection->prepare("SELECT user_id FROM users WHERE email = ?");
    $statement->bind_param("s",$email);

    $statement->execute();

    $statement->store_result();
    if ($statement->num_rows > 0){
        $email_error = "Email is already used";
        $error = true;
    }
    $statement->close();

    if (strlen($password) < 6){
        $password_error = "Password must have at least 6 characters";
        $error = true;
    }
    if ($confirm_password != $password){
        $confirm_password_error = "Password and Confirm Password do not match";
        $error = true;
    }

    

    if (!$error){
        $password = password_hash($password, PASSWORD_DEFAULT);
        $date_joined = date('Y-m-d H:i:s');

        $statement = $dbConnection->prepare(
            "INSERT INTO users (username, email, password, date_joined)". 
                "VALUES (?, ?, ?, ?)"
        );

        $statement->bind_param('ssss', $username, $email, $password, $date_joined);
        $statement->execute();
        $insert_id = $statement->insert_id;
        $statement->close();

        $_SESSION["user_id"]=$insert_id;
        $_SESSION["username"]=$username;
        $_SESSION["email"]=$email;
        $_SESSION["date_joined"]=$date_joined;

        $successMessage = "User registered successfully.";
        header("location: ../login.php");
        exit;
    }
}
?>
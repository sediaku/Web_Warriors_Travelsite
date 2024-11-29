<?php
session_start();
include '../db/db-config.php';
$dbConnection = getDatabaseConnection();
include '../functions/userdetails.php'; // Fetch user details

// Get user ID from session or query parameter
$profileUserId = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
$loggedInUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$profileUserId && !$loggedInUserId) {
    header("Location: login.php"); // Redirect if no user is specified or logged in
    exit();
}

// Fetch the profile user details
$userIdToView = $profileUserId ?? $loggedInUserId; // View logged-in user's profile if not specified
$userDetails = getUserDetails($userIdToView, $dbConnection);

if (!$userDetails) {
    echo "User not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $userId = $_SESSION['user_id']; // Get the logged-in user's ID
    $uploadDir = '../assets/images/profile_pics/'; // Directory to store profile pictures
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // Allowed image types

    // Check if the file was uploaded without errors
    if ($_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpName = $_FILES['profile_picture']['tmp_name'];
        $fileName = basename($_FILES['profile_picture']['name']);
        $fileType = mime_content_type($fileTmpName);
        $filePath = $uploadDir . $fileName;

        // Check if the file type is allowed
        if (in_array($fileType, $allowedTypes)) {
            // Move the uploaded file to the desired directory
            if (move_uploaded_file($fileTmpName, $filePath)) {
                // Update the user's profile picture in the database
                $query = "UPDATE users SET profile_picture = ? WHERE user_id = ?";
                $stmt = $dbConnection->prepare($query);
                $stmt->bind_param("si", $filePath, $userId);
                if ($stmt->execute()) {
                    $_SESSION['profile_picture'] = $filePath; // Update session to reflect the change
                    header("Location: view-profile.php?user_id=$userId");
                    exit();
                } else {
                    echo "Error updating profile picture.";
                }
            } else {
                echo "Failed to upload the image.";
            }
        } else {
            echo "Invalid file type. Only JPG, PNG, and GIF files are allowed.";
        }
    } else {
        echo "Error uploading file.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bio'])) {
    $userId = $_SESSION['user_id']; // Get the logged-in user's ID
    $newBio = trim($_POST['bio']); // Get the new bio from the form

    // Update the bio in the database
    $query = "UPDATE users SET bio = ? WHERE user_id = ?";
    $stmt = $dbConnection->prepare($query);
    $stmt->bind_param("si", $newBio, $userId);

    if ($stmt->execute()) {
        $_SESSION['bio'] = $newBio; // Update session with new bio
        header("Location: view-profile.php?user_id=$userId");
        exit();
    } else {
        echo "Error updating bio.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($userDetails['username']); ?>'s Profile</title>
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/profile-style.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
        <style>
        .profile-pic {
            width: 200px; 
            height: 100px; 
            object-fit: cover; 
            border-radius: 0%; 
            }
        </style>
    </head>
    <body>
        <header>
            <?php 
                // Check if user is logged in and assign the appropriate navbar
                if (isset($_SESSION['user_id'])) {
                    if ($_SESSION['role'] == 2) {
                        include 'admin/admin-navbar.php';  // For admin users
                    } else {
                        include 'navbar_in.php';   // For normal logged-in users
                    }
                } else {
                    include 'navbar_guest.php';   // For logged-out users
                }
            ?>
        </header>

        <section class="profile-view">
            <div class="left">
                <!-- Profile Picture -->
                <div id="pfp">
                    <img id = "pic" src="<?php 
                        echo htmlspecialchars(
                            $userDetails['profile_picture'] 
                            ? $userDetails['profile_picture'] 
                            : '../assets/images/default-profile.jpg'
                        ); 
                    ?>" alt="Profile Picture" class="profile-pic">
                </div>

                <!-- Edit option if logged in user is viewing their own profile -->
                <?php if ($loggedInUserId === $userIdToView): ?>
                    <form method="post" enctype="multipart/form-data">
                        <input type="file" name="profile_picture" accept="image/*">
                        <button type="submit"style="font-size: 0.5rem;">Update Profile Picture</button>
                    </form>
                <?php endif; ?>
            </div>

            <div class="right">
                <!-- Bio Section -->
                <div class="bio-section">
                    <h2>Bio</h2>
                    <p class="bio">
                        <?php echo htmlspecialchars($userDetails['bio'] ?? 'No bio available.'); ?>
                    </p>

                    <!-- Edit option for bio if logged in user is viewing their own profile -->
                    <?php if ($loggedInUserId === $userIdToView): ?>
                        <form method="post">
                            <textarea name="bio" rows="4" placeholder="Update your bio"><?php echo htmlspecialchars($userDetails['bio']); ?></textarea>
                            <button type="submit" style="font-size: 0.5rem;">Update Bio</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <footer>
            <?php include 'footer.php'; ?>
        </footer>
    </body>
</html>

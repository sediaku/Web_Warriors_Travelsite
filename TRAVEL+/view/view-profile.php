<?php
session_start();
include '../db/db-config.php'; // Database connection
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
    </head>
    <body>
        <header>
            <?php include 'navbar_in.php'; ?>
        </header>

        <section class="profile-view">
            <div class="left">
                <!-- Profile Picture -->
                <div id="pfp">
                    <img src="<?php 
                        echo htmlspecialchars(
                            $userDetails['profile_picture'] 
                            ? $userDetails['profile_picture'] 
                            : '../assets/images/default-profile.png'
                        ); 
                    ?>" alt="Profile Picture" class="profile-pic">
                </div>

                <!-- Edit option if logged in user is viewing their own profile -->
                <?php if ($loggedInUserId === $userIdToView): ?>
                    <form action="../functions/update-profile.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="profile_picture" accept="image/*">
                        <button type="submit" class="edit-btns">Update Profile Picture</button>
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
                        <form action="../functions/update-bio.php" method="post">
                            <textarea name="bio" rows="4" placeholder="Update your bio"><?php echo htmlspecialchars($userDetails['bio']); ?></textarea>
                            <button type="submit" class="edit-btns">Update Bio</button>
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

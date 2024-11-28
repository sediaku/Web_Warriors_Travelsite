<?php

session_start();
include '../db/db-config.php';
$dbConnection = getDatabaseConnection();

// Fetch the blog ID from the URL (e.g., blog-view.php?blog_id=1)
$blogId = isset($_GET['blog_id']) ? intval($_GET['blog_id']) : 0;

if ($blogId === 0) {
    echo "Invalid blog ID.";
    exit;
}

// Fetch blog data
$query = "
    SELECT 
        b.title, 
        b.content, 
        b.published_date, 
        b.location_id, 
        b.user_id, 
        u.username AS author_name, 
        l.location_name AS location_name 
    FROM 
        blog b
    JOIN 
        users u ON b.user_id = u.user_id
    LEFT JOIN 
        locations l ON b.location_id = l.location_id
    WHERE 
        b.blog_id = ?";
$stmt = $dbConnection->prepare($query);
$stmt->bind_param("i", $blogId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $blog = $result->fetch_assoc();
} else {
    echo "Blog post not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($blog['title']); ?></title>
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/blog-style.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
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

        <section class="blog-view">
            <div class="left">
                <div class="author">
                    <a href="view-profile.php?user_id=<?php echo htmlspecialchars($blog['user_id']); ?>">
                        <span class="material-symbols-outlined">person</span>
                        <?php echo htmlspecialchars($blog['author_name']); ?>
                    </a>
                </div>
                <div class="location">
                    <?php if (!empty($blog['location_name'])): ?>
                        <a href="view-location.php?location_id=<?php echo htmlspecialchars($blog['location_id']); ?>">
                            <span class="material-symbols-outlined">place</span>
                            <?php echo htmlspecialchars($blog['location_name']); ?>
                        </a>
                    <?php else: ?>
                        <span class="material-symbols-outlined">location_off</span> Location not specified.
                    <?php endif; ?>
                </div>
                <div class="date">
                    <span class="material-symbols-outlined">calendar_today</span>
                    <?php echo htmlspecialchars(date("F j, Y", strtotime($blog['published_date']))); ?>
                </div>
            </div>

            <div class="right">
                <div class="post">
                    <h1><?php echo htmlspecialchars($blog['title']); ?></h1>
                    <p><?php echo nl2br(htmlspecialchars($blog['content'])); ?></p>
                </div>
            </div>
        </section>

        <footer>
            <?php include 'footer.php'; ?>
        </footer>

    </body>
</html>

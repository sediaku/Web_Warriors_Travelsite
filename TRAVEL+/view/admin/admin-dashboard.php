<?php
include '../../db/db-config.php';
$dbConnection = getDatabaseConnection();
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    die("You do not have permission to access this page.");
}

$userId = $_SESSION['user_id'];

// Fetch total number of users
$totalUsersQuery = "SELECT COUNT(*) AS total_users FROM users";
$totalUsersResult = $dbConnection->query($totalUsersQuery);
$totalUsers = $totalUsersResult->fetch_assoc()['total_users'];

// Fetch total number of locations
$totalLocationsQuery = "SELECT COUNT(*) AS total_locations FROM locations";
$totalLocationsResult = $dbConnection->query($totalLocationsQuery);
$totalLocations = $totalLocationsResult->fetch_assoc()['total_locations'];

// Fetch average daily blog posts this week
$avgPostsQuery = "
    SELECT COUNT(*) / 7 AS avg_daily_posts 
    FROM blog 
    WHERE published_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
$avgPostsResult = $dbConnection->query($avgPostsQuery);
$avgDailyPosts = number_format($avgPostsResult->fetch_assoc()['avg_daily_posts'], 2);

// Fetch total number of blog posts
$totalBlogsQuery = "SELECT COUNT(*) AS total_blogs FROM blog";
$totalBlogsResult = $dbConnection->query($totalBlogsQuery);
$totalBlogs = $totalBlogsResult->fetch_assoc()['total_blogs'];

// Fetch user's blog posts
$userBlogsQuery = "
    SELECT blog_id, title, published_date 
    FROM blog 
    WHERE user_id = ? 
    ORDER BY published_date DESC";
$userBlogsStmt = $dbConnection->prepare($userBlogsQuery);
$userBlogsStmt->bind_param("i", $userId);
$userBlogsStmt->execute();
$userBlogsResult = $userBlogsStmt->get_result();

// Fetch user's reviews
$userReviewsQuery = "
    SELECT reviews.review_id, reviews.review_text, reviews.rating, reviews.review_date, locations.location_name 
    FROM reviews 
    INNER JOIN locations ON reviews.location_id = locations.location_id 
    WHERE reviews.user_id = ? 
    ORDER BY reviews.review_date DESC";
$userReviewsStmt = $dbConnection->prepare($userReviewsQuery);
$userReviewsStmt->bind_param("i", $userId);
$userReviewsStmt->execute();
$userReviewsResult = $userReviewsStmt->get_result();

// Fetch top users (most reviews and blogs)
$topUsersQuery = "
    SELECT u.username, 
           (SELECT COUNT(*) FROM reviews WHERE reviews.user_id = u.user_id) AS total_reviews, 
           (SELECT COUNT(*) FROM blog WHERE blog.user_id = u.user_id) AS total_blogs 
    FROM users u 
    ORDER BY total_reviews + total_blogs DESC 
    LIMIT 10";
$topUsersResult = $dbConnection->query($topUsersQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/admin-dashboard-style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
<body>
<header>
    <?php include 'admin-navbar.php'; ?>
</header>

<section class="admin-dash">
    <div class="main">
        <h1>Statistics</h1>
        <div class="stats">
            <div class="users">
                <h2>Total Number of Users</h2>
                <p><?php echo $totalUsers; ?></p>
            </div>
            <div class="locations">
                <h2>Total Number of Locations</h2>
                <p><?php echo $totalLocations; ?></p>
            </div>
            <div class="avg-posts">
                <h2>Average Daily Number of Blog Posts This Week</h2>
                <p><?php echo $avgDailyPosts; ?></p>
            </div>
            <div class="blog-posts">
                <h2>Total Number of Blog Posts</h2>
                <p><?php echo $totalBlogs; ?></p>
            </div>
        </div>

        <div class="personal">
            <h1>My Posts</h1>
            <div class="posts">
                <?php if ($userBlogsResult->num_rows > 0): ?>
                    <ul>
                        <?php while ($blog = $userBlogsResult->fetch_assoc()): ?>
                            <li>
                                <a href="../view-blog-post.php?blog_id=<?php echo $blog['blog_id']; ?>">
                                    <?php echo htmlspecialchars($blog['title']); ?>
                                </a> - <?php echo htmlspecialchars($blog['published_date']); ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>You have no blogs.</p>
                <?php endif; ?>
            </div>

            <h1>My Reviews</h1>
            <div class="reviews">
                <?php if ($userReviewsResult->num_rows > 0): ?>
                    <ul>
                        <?php while ($review = $userReviewsResult->fetch_assoc()): ?>
                            <li><?php echo htmlspecialchars($review['location_name']); ?> - 
                                <?php echo htmlspecialchars($review['rating']); ?>/5</li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>You have no reviews.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="active-users">
        <h1>Top Users</h1>
        <?php if ($topUsersResult->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Total Reviews</th>
                        <th>Total Blogs</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $topUsersResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['total_reviews']); ?></td>
                            <td><?php echo htmlspecialchars($user['total_blogs']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No top users found.</p>
        <?php endif; ?>
    </div>
</section>
</body>
</html>


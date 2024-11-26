<?php
include '../db/db-config.php';
$dbConnection = getDatabaseConnection();
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view your dashboard.");
}

$userId = $_SESSION['user_id']; 
$userRole = $_SESSION['role']; 

// Check if the user has the correct role
if ($userRole != 1) {
    die("You do not have permission to access this page.");
}

// Fetch blogs for the logged-in user
$blogsQuery = "
    SELECT blog_id, title, published_date 
    FROM blog
    WHERE user_id = ? 
    ORDER BY published_date DESC";
$blogsStmt = $dbConnection->prepare($blogsQuery);
$blogsStmt->bind_param("i", $userId);
$blogsStmt->execute();
$blogsResult = $blogsStmt->get_result();

// Fetch reviews for the logged-in user
$reviewsQuery = "
    SELECT 
        reviews.review_id,
        reviews.review_text,
        reviews.rating,
        reviews.review_date,
        reviews.likes,
        reviews.comments,
        locations.location_name 
    FROM reviews 
    INNER JOIN locations ON reviews.location_id = locations.location_id 
    WHERE reviews.user_id = ? 
    ORDER BY reviews.review_date DESC";
$reviewsStmt = $dbConnection->prepare($reviewsQuery);
$reviewsStmt->bind_param("i", $userId);
$reviewsStmt->execute();
$reviewsResult = $reviewsStmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
<body>
<header>
    <?php include 'navbar_in.php'; ?>
</header>

<main>
    <h1>Welcome to Your Dashboard</h1>

    <!-- Blogs Section -->
    <?php if ($blogsResult->num_rows > 0): ?>
        <h2>Your Blogs</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Published Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($blog = $blogsResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($blog['title']); ?></td>
                        <td><?php echo htmlspecialchars($blog['published_date']); ?></td>
                        <td>
                            <a href="view-blog-post.php?blog_id=<?php echo htmlspecialchars($blog['blog_id']); ?>">View</a>
                            <form action="../functions/delete-blog.php" method="POST" style="display:inline;">
                                <input type="hidden" name="blog_id" value="<?php echo htmlspecialchars($blog['blog_id']); ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this blog?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no blogs.</p>
    <?php endif; ?>

    <!-- Reviews Section -->
    <?php if ($reviewsResult->num_rows > 0): ?>
        <h2>Your Reviews</h2>
        <table>
            <thead>
                <tr>
                    <th>Location</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Date</th>
                    <th>Likes</th>
                    <th>Comments</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($review = $reviewsResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($review['location_name']); ?></td>
                        <td><?php echo htmlspecialchars($review['rating']); ?>/5</td>
                        <td><?php echo htmlspecialchars($review['review_text']); ?></td>
                        <td><?php echo htmlspecialchars($review['review_date']); ?></td>
                        <td>
                            <form action="../functions/delete-review.php" method="POST" style="display:inline;">
                                <input type="hidden" name="review_id" value="<?php echo htmlspecialchars($review['review_id']); ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this review?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no reviews.</p>
    <?php endif; ?>

    <?php
    $blogsStmt->close();
    $reviewsStmt->close();
    $dbConnection->close();
    ?>
</main>
<footer>
    <?php include 'footer.php'; ?>
</footer>
</body>
</html>


<?php
session_start();
include '../db/db-config.php';
$dbConnection = getDatabaseConnection();
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
    <?php
    if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
        if ($_SESSION['role'] == 1) {
            include 'navbar_in.php';
        } else {
            include '../view/admin/admin-navbar.php';
        }
    } else {
        include 'navbar_guest.php';
    }
    ?>
</header>

<h1>Welcome to Your Dashboard</h1>

<?php
if (!isset($_SESSION['user_id'])) {
    echo "<p>You must be logged in to view your dashboard.</p>";
    exit;
}

$userId = $_SESSION['user_id']; 

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

<!-- Blogs Section -->
<?php
if ($blogsResult->num_rows > 0) {
    echo "<h2>Your Blogs</h2>";
    echo "<table class='dashboard-table'>";
    echo "<thead><tr><th>Title</th><th>Published Date</th><th>Actions</th></tr></thead>";
    echo "<tbody>";
    
    while ($blog = $blogsResult->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($blog['title']) . "</td>";
        echo "<td>" . htmlspecialchars($blog['published_date']) . "</td>";
        echo "<td>
                <a href='view-blog-post.php?blog_id=" . htmlspecialchars($blog['blog_id']) . "'>View</a>
                <form action='../functions/delete-blog.php' method='POST' style='display:inline;'>
                    <input type='hidden' name='blog_id' value='" . htmlspecialchars($blog['blog_id']) . "'>
                    <button type='submit' onclick=\"return confirm('Are you sure you want to delete this blog?');\">Delete</button>
                </form>
              </td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p>You have no blogs.</p>";
}
?>

<!-- Reviews Section -->
<?php
if ($reviewsResult->num_rows > 0) {
    echo "<h2>Your Reviews</h2>";
    echo "<table class='dashboard-table'>";
    echo "<thead><tr><th>Location</th><th>Rating</th><th>Review</th><th>Date</th><th>Actions</th></tr></thead>";
    echo "<tbody>";

    while ($review = $reviewsResult->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($review['location_name']) . "</td>";
        echo "<td>" . htmlspecialchars($review['rating']) . "/5</td>";
        echo "<td>" . htmlspecialchars($review['review_text']) . "</td>";
        echo "<td>" . htmlspecialchars($review['review_date']) . "</td>";
        echo "<td>
                <form action='../functions/delete-review.php' method='POST' style='display:inline;'>
                    <input type='hidden' name='review_id' value='" . htmlspecialchars($review['review_id']) . "'>
                    <button type='submit' onclick=\"return confirm('Are you sure you want to delete this review?');\">Delete</button>
                </form>
              </td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p>You have no reviews.</p>";
}

$blogsStmt->close();
$reviewsStmt->close();
$dbConnection->close();
?>

<script src="../assets/js/navbar-in.js"></script>
</body>
</html>

<?php
// Include database connection
include '../../db/db-config.php';
$dbConnection = getDatabaseConnection();
session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    die("Access denied. You do not have permission to view this page.");
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

// Fetch top 5 users based on blog posts
$topUsersQuery = "
    SELECT u.user_id, u.username, COUNT(b.blog_id) AS total_posts
    FROM users u
    LEFT JOIN blog b ON u.user_id = b.user_id
    GROUP BY u.user_id
    ORDER BY total_posts DESC
    LIMIT 5";
$topUsersResult = $dbConnection->query($topUsersQuery);

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
    SELECT reviews.review_id, reviews.review_text, reviews.rating, reviews.review_date, locations.location_name, locations.location_id  
    FROM reviews  
    INNER JOIN locations ON reviews.location_id = locations.location_id  
    WHERE reviews.user_id = ?  
    ORDER BY reviews.review_date DESC";
$userReviewsStmt = $dbConnection->prepare($userReviewsQuery);
$userReviewsStmt->bind_param("i", $userId);
$userReviewsStmt->execute();
$userReviewsResult = $userReviewsStmt->get_result();
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

        <!-- User's Blogs Section -->
        <div class="personal">
            <h1>My Blogs</h1>
            <div class="manage-blogs">
                <?php if ($userBlogsResult->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Published Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($blog = $userBlogsResult->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($blog['title']); ?></td>
                                    <td><?php echo htmlspecialchars($blog['published_date']); ?></td>
                                    <td>
                                        <a href="../view-blog-post.php?blog_id=<?php echo $blog['blog_id']; ?>">View More</a>
                                        <form method="POST" action="../../functions/delete-blog.php" style="display:inline;">
                                            <input type="hidden" name="blog_id" value="<?php echo $blog['blog_id']; ?>">
                                            <button type="submit" class="delete-button">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>You have no blogs.</p>
                <?php endif; ?>
            </div>
            <!-- Add Blog Button and Modal -->
            <div class="add-blog-button">
                <button class="add-blog-link" onclick="openModal()">Add New Blog</button>
            </div>

            <!-- Add Blog Modal -->
            <div id="addBlogModal" class="modal">
                <div class="modal-content">
                    <span class="close-button" onclick="closeModal()">&times;</span>
                    <h2>Add New Blog</h2>
                    <form action="../../functions/add-blog.php" method="POST">
                        <label for="blogTitle">Blog Title:</label>
                        <input type="text" id="blogTitle" name="blog_title" required>

                        <label for="blogContent">Content:</label>
                        <textarea id="blogContent" name="blog_content" rows="5" required></textarea>

                        <label for="blogLocation">Location:</label>
                        <select id="blogLocation" name="location_id" required>
                            <?php
                            $locationsQuery = "SELECT location_id, location_name FROM locations";
                            $locationsResult = $dbConnection->query($locationsQuery);
                            while ($location = $locationsResult->fetch_assoc()) {
                                echo "<option value='" . $location['location_id'] . "'>" . $location['location_name'] . "</option>";
                            }
                            ?>
                        </select>

                        <button type="submit" class="submit-button">Submit</button>
                    </form>
                </div>
            </div>

            <!-- Reviews Section -->
            <h1>My Reviews</h1>
            <div class="manage-reviews">
                <?php if ($userReviewsResult->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Location</th>
                                <th>Review Text</th>
                                <th>Rating</th>
                                <th>Review Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($review = $userReviewsResult->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($review['location_name']); ?></td>
                                    <td><?php echo htmlspecialchars($review['review_text']); ?></td>
                                    <td><?php echo htmlspecialchars($review['rating']); ?>/5</td>
                                    <td><?php echo htmlspecialchars($review['review_date']); ?></td>
                                    <td>
                                        <a href="../view-location.php?location_id=<?php echo $review['location_id']; ?>">View More</a>
                                        <form method="POST" action="../../functions/delete-review.php" style="display:inline;">
                                            <input type="hidden" name="review_id" value="<?php echo $review['review_id']; ?>">
                                            <button type="submit" class="delete-button">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>You have no reviews.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    

    <!-- Top 5 Users Section -->
    <div class="top-users">
    <h2>Top 5 Users</h2>
    <?php if ($topUsersResult->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Total Blog Posts</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $topUsersResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['total_posts']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
    </div>
    <!-- Add Location-->
    <div class="location-management">
    <div class="location-actions">
        <form action="../add-location.php" method="get">
            <button type="submit" class="btn btn-green">Add New Location</button>
        </form>
    </div>
</div>

</section>

<script>
    function openModal() {
        document.getElementById("addBlogModal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("addBlogModal").style.display = "none";
    }
</script>

</body>
</html>

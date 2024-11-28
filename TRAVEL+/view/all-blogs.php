<?php

session_start(); 
include '../db/db-config.php';

try {
    $dbConnection = getDatabaseConnection();

    // Fetch all blogs with user names
    $query = "
        SELECT blog.blog_id, blog.title, blog.content, users.username AS author 
        FROM blog 
        JOIN users ON blog.user_id = users.user_id";
        
    $stmt = $dbConnection->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $blogs = [];
    while ($row = $result->fetch_assoc()) {
        $blogs[] = $row;
    }

    $stmt->close();
    $dbConnection->close();
} catch (Exception $e) {
    die("Error fetching blogs: " . htmlspecialchars($e->getMessage()));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Blogs</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/all-blogs-style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
<body>
<header>
    <?php 
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

<section class="all-blogs">
    <h1>Explore All Blogs</h1>

    <!-- Search Bar -->
    <div class="search-container">
        <input 
            type="text" 
            id="search-bar" 
            class="search-bar" 
            placeholder="Search blogs..."
            onkeyup="searchBlogs()"
        >
    </div>

    <!-- Results Container -->
    <div id="search-results" class="blogs-container">
        <?php if (!empty($blogs)): ?>
            <?php foreach ($blogs as $blog): ?>
                <div class="blog-card">
                    <div class="blog-header">
                        <h2><?php echo htmlspecialchars($blog['title']); ?></h2>
                        <p>By: <?php echo htmlspecialchars($blog['author']); ?></p>
                    </div>
                    <div class="blog-content">
                        <p><?php echo htmlspecialchars(substr($blog['content'], 0, 150)); ?>...</p>
                    </div>
                    <div class="blog-actions">
                        <a href="view-blog-post.php?blog_id=<?php echo $blog['blog_id']; ?>" class="details-btn">Read More</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No blogs available at the moment.</p>
        <?php endif; ?>
    </div>
</section>

<script>
    // Function to search blogs dynamically
    function searchBlogs() {
        const query = document.getElementById('search-bar').value;

        // Use AJAX to send search query to the backend
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "../functions/search-blogs.php?q=" + encodeURIComponent(query), true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                // Update the results container with the response
                document.getElementById('search-results').innerHTML = xhr.responseText;
            }
        };

        xhr.send();
    }
</script>

<footer>
    <?php include 'footer.php'; ?>
</footer>
</body>
</html>


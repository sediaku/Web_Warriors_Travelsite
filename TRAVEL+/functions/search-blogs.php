<?php

include '../db/db-config.php';

// Get search query
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

try {
    $dbConnection = getDatabaseConnection();

    // Fetch matching blogs with user names
    $sql = "
        SELECT blog.blog_id, blog.title, blog.content, users.username AS author 
        FROM blog 
        JOIN users ON blog.user_id = users.user_id
        WHERE blog.title LIKE ? OR blog.content LIKE ? OR users.username LIKE ?";
        
    $stmt = $dbConnection->prepare($sql);
    $searchTerm = '%' . $query . '%';
    $stmt->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
                <div class='blog-card'>
                    <div class='blog-header'>
                        <h2>" . htmlspecialchars($row['title']) . "</h2>
                        <p>By: " . htmlspecialchars($row['author']) . "</p>
                    </div>
                    <div class='blog-content'>
                        <p>" . htmlspecialchars(substr($row['content'], 0, 150)) . "...</p>
                    </div>
                    <div class='blog-actions'>
                        <a href='view-blog-post.php?blog_id=" . $row['blog_id'] . "' class='details-btn'>Read More</a>
                    </div>
                </div>
            ";
        }
    } else {
        echo "<p>No blogs match your search query.</p>";
    }

    $stmt->close();
    $dbConnection->close();
} catch (Exception $e) {
    echo "<p>Error fetching search results: " . htmlspecialchars($e->getMessage()) . "</p>";
}

?>

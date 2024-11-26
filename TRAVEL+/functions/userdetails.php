<?php
function getUserDetails($userId, $conn) {
    // Fetch user details
    $query = "SELECT username, profile_picture, bio FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $userDetails = $stmt->get_result()->fetch_assoc();

    if (!$userDetails) {
        return null; // Return null if user not found
    }

    // Fetch user's blog posts
    $query = "SELECT post_id, title, created_at FROM blog_posts WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $userDetails['posts'] = $posts; // Add posts to the user details
    return $userDetails;
}
?>

<?php
include '../../db/db-config.php';
session_start();

// Check if user is logged in and has admin privileges (role = 2)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    die("Access denied. You do not have permission to view this page.");
}

$dbConnection = getDatabaseConnection();

// Fetch all users
$usersQuery = "SELECT * FROM users";
$stmt = $dbConnection->prepare($usersQuery);
$stmt->execute();
$usersResult = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
<body>
    <header>
        <?php include 'admin-navbar.php'; ?>
    </header>

    <main>
        <h1>User Management</h1>

        <?php if ($usersResult->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Profile Picture</th>
                        <th>Date Joined</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $usersResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <?php if ($user['profile_picture']): ?>
                                    <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" width="50" height="50">
                                <?php else: ?>
                                    No picture
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($user['date_joined']); ?></td>
                            <td><?php echo htmlspecialchars($user['last_login']); ?></td>
                            <td>
                                <a href="view-profile.php?user_id=<?php echo urlencode($user['user_id']); ?>">View Profile</a> | 
                                <form action="delete-user.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>">
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
    </main>
</body>
</html>

<?php
$dbConnection->close();
?>


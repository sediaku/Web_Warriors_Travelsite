<?php
include '../../db/db-config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    die("Access denied. You do not have permission to view this page.");
}

$dbConnection = getDatabaseConnection();

// Query to fetch locations with average rating
$locationsQuery = "
    SELECT 
        locations.*,
        COALESCE(AVG(reviews.rating), 0) AS average_rating
    FROM 
        locations
    LEFT JOIN 
        reviews ON locations.location_id = reviews.location_id
    GROUP BY 
        locations.location_id
    ORDER BY 
        locations.location_name ASC";

$locationsStmt = $dbConnection->prepare($locationsQuery);
$locationsStmt->execute();
$locationsResult = $locationsStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location Management</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
</head>

<body>
<header>
    <?php include 'admin-navbar.php'; ?>
</header>

<main class="manage-locations">
    <h1>Location Management</h1>

    <?php if ($locationsResult->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Location Name</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>Country</th>
                    <th>Category</th>
                    <th>Average Rating</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($location = $locationsResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($location['location_name']); ?></td>
                        <td><?php echo htmlspecialchars($location['address']); ?></td>
                        <td><?php echo htmlspecialchars($location['city']); ?></td>
                        <td><?php echo htmlspecialchars($location['country']); ?></td>
                        <td><?php echo htmlspecialchars($location['category']); ?></td>
                        <td>
                            <?php 
                                echo $location['average_rating'] > 0 
                                    ? htmlspecialchars(number_format($location['average_rating'], 2)) 
                                    : "N/A"; 
                            ?>
                        </td>
                        <td>
                            <a href="../view-location.php?location_id=<?php echo htmlspecialchars($location['location_id']); ?>">View More</a>
                            <a href="../edit-location.php?location_id=<?php echo htmlspecialchars($location['location_id']); ?>">Edit</a>
                            <form action="../../functions/delete-location.php" method="POST" style="display:inline;">
                                <input type="hidden" name="location_id" value="<?php echo htmlspecialchars($location['location_id']); ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this location?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No locations found.</p>
    <?php endif; ?>

    <?php
    $locationsStmt->close();
    $dbConnection->close();
    ?>
</main>
</body>
</html>

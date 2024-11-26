<?php
include '../db/db-config.php';
$dbConnection = getDatabaseConnection();
session_start();

// Check if user is logged in and has admin privileges (role = 2)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    die("Access denied. You do not have permission to view this page.");
}

// Fetch the location ID
$locationId = isset($_GET['location_id']) ? intval($_GET['location_id']) : 0;
if ($locationId <= 0) {
    die("Invalid location ID.");
}

// Fetch location details
$locationQuery = "SELECT * FROM locations WHERE location_id = ?";
$stmt = $dbConnection->prepare($locationQuery);
$stmt->bind_param("i", $locationId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Location not found.");
}
$location = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $locationName = $_POST['location_name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $openingHours = $_POST['opening_hours'];
    $bookingAllowed = isset($_POST['booking_allowed']) ? 1 : 0;
    $contactInfo = $_POST['contact_info'];
    $priceRange = $_POST['price_range'];
    $bookingLink = $_POST['booking_link'];

    $updateQuery = "
        UPDATE locations 
        SET 
            location_name = ?, 
            address = ?, 
            city = ?, 
            country = ?, 
            description = ?, 
            category = ?, 
            opening_hours = ?, 
            booking_allowed = ?, 
            contact_info = ?, 
            price_range = ?, 
            booking_link = ?
        WHERE location_id = ?";
    $updateStmt = $dbConnection->prepare($updateQuery);
    $updateStmt->bind_param(
        "ssssssssssss",
        $locationName,
        $address,
        $city,
        $country,
        $description,
        $category,
        $openingHours,
        $bookingAllowed,
        $contactInfo,
        $priceRange,
        $bookingLink,
        $locationId
    );

    if ($updateStmt->execute()) {
        echo "<p>Location updated successfully!</p>";
        header("Location: admin/location-management.php");
        exit;
    } else {
        echo "<p>Failed to update location.</p>";
    }
}

$stmt->close();
$dbConnection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Location</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/edit-location.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
<body>
<header>
    <?php include 'admin/admin-navbar.php'; ?>
</header>

<main>
    <h1>Edit Location</h1>
    <form method="POST">
        <label for="location_name">Location Name:</label>
        <input type="text" id="location_name" name="location_name" value="<?php echo htmlspecialchars($location['location_name']); ?>" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($location['address']); ?>" required>

        <label for="city">City:</label>
        <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($location['city']); ?>" required>

        <label for="country">Country:</label>
        <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($location['country']); ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($location['description']); ?></textarea>

        <label for="category">Category:</label>
        <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($location['category']); ?>" required>

        <label for="opening_hours">Opening Hours:</label>
        <input type="text" id="opening_hours" name="opening_hours" value="<?php echo htmlspecialchars($location['opening_hours']); ?>" required>

        <label for="contact_info">Contact Info:</label>
        <input type="text" id="contact_info" name="contact_info" value="<?php echo htmlspecialchars($location['contact_info']); ?>" required>

        <label for="price_range">Price Range:</label>
        <input type="text" id="price_range" name="price_range" value="<?php echo htmlspecialchars($location['price_range']); ?>" required>

        <label for="booking_link">Booking Link:</label>
        <input type="text" id="booking_link" name="booking_link" value="<?php echo htmlspecialchars($location['booking_link']); ?>" required>

        <button type="submit">Save Changes</button>
    </form>
</main>

<footer>
    <?php include 'footer.php'; ?>
</footer>

</body>
</html>

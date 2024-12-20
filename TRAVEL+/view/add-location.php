<?php
include '../db/db-config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch form data and sanitize input
    $locationName = trim($_POST['location_name']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $country = trim($_POST['country']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $openingHours = trim($_POST['opening_hours']);
    $contactInfo = trim($_POST['contact_info']);
    $priceRange = trim($_POST['price_range']);
    $bookingLink = trim($_POST['booking_link']);
    $average_rating = trim($_POST['average_rating']); // Corrected variable name

    // Validate inputs
    if (empty($locationName) || empty($city) || empty($country) || empty($description)) {
        $error = "These entries are required: Location name, City, Country, and Description";
    } else {
        try {
            // Connect to the database
            $dbConnection = getDatabaseConnection();
            
            // Insert the new location
            $query = "INSERT INTO locations (location_name, `address`, city, country, category, `description`, opening_hours, contact_info, price_range, booking_link, average_rating) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $dbConnection->prepare($query);
            $stmt->bind_param("sssssssssss", $locationName, $address, $city, $country, $category, $description, $openingHours, $contactInfo, $priceRange, $bookingLink, $average_rating); // Corrected bind_param to match placeholders
            
            if ($stmt->execute()) {
                $success = "Location added successfully!";
                header("Location: admin/admin-dashboard.php?success=" . urlencode($success));
                exit;
            } else {
                $error = "Failed to add location. Please try again.";
            }
            
            $stmt->close();
            $dbConnection->close();
        } catch (Exception $e) {
            $error = "Error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Location</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/add-location.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
<body>
<header>
<?php 
        // Check if user is logged in and assign the appropriate navbar
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role'] == 2) {
                include '../view/admin/admin-navbar.php';  // For admin users
            } else {
                include 'navbar_in.php';   // For normal logged-in users
            }
        } else {
            include 'navbar_guest.php';   // For logged-out users
        }
    ?>
</header>

    <section class="add-location">
        <h1>Add New Location</h1>
        
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <form method="POST" action="add-location.php">
            <div class = "input-field">
                <label for="location_name">Location Name</label>
                <input type="text" id="location_name" name="location_name" required>
            </div>
            
            <div class = "input-field">
                <label for="address">Address</label>
                <input type="text" id="address" name="address">
            </div>

            <div class = "input-field">
                <label for="city">City</label>
                <input type="text" id="city" name="city" required>
            </div>

            <div class = "input-field">
                <label for="country">Country</label>
                <input type="text" id="country" name="country" required>
            </div>

            <div class = "input-field">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="Museum">Museum</option>
                    <option value="Park">Park</option>
                    <option value="Restaurant">Restaurant</option>
                    <option value="Beach">Beach</option>
                    <option value="Hotel">Hotel</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <div>
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <div>
            <label for="opening_hours">Opening Hours</label>
            <input type="text" id="opening_hours" name="opening_hours" placeholder="e.g., Mon-Fri 9am-5pm">
            </div>

            <div>
                <label for="contact_info">Contact_info</label>
                <input type="text" id="contact_info" name="contact_info" placeholder="Email or Phone Number">
            </div>

            <div>
                <label for="price_range">Price range</label>
                <select id="price_range" name="price_range">
                    <option value="GHC0 - GHC500">GHC0 - GHC500</option>
                    <option value="GHC500 - GHC1000">GHC500 - GHC1000</option>
                    <option value="GHC1000 - GHC2000">GHC1000 - GHC2000</option>
                    <option value="GHC2000 - GHC5000">GHC2000 - GHC5000</option>
                    <option value="GHC5000 - GHC10000">GHC5000 - GHC10000</option>
                    <option value="Above 10000">Above 10000</option>
                </select>
            </div>

            <div>
                <label for="booking_link">Booking Link</label>
                <input type="url" id="booking_link" name="booking_link">
            </div>

            <div>
                <label for="average_rating">Average Rating</label>
                <input type="text" id="average_rating" name="average_rating">
            </div>
            
            <button type="submit">Add Location</button>
        </form>
    </section>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>

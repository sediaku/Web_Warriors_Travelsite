<?php
// Include database connection
include '../db/db-config.php';
$dbConnection = getDatabaseConnection();
session_start();


// Fetch the location ID from URL
$locationId = isset($_GET['location_id']) ? intval($_GET['location_id']) : 0;
if ($locationId <= 0) {
    die("Invalid location ID.");
}

// Function to retrieve location pictures
function getLocationPictures($locationId, $dbConnection) {
    $picturesQuery = "SELECT picture FROM location_pictures WHERE location_id = ?";
    $stmt = $dbConnection->prepare($picturesQuery);
    $stmt->bind_param("i", $locationId);
    $stmt->execute();
    $result = $stmt->get_result();

    $pictures = [];
    while ($row = $result->fetch_assoc()) {
        $pictures[] = $row['picture'];
    }

    $stmt->close();
    return $pictures;
}

// Retrieve pictures for the current location
$locationPictures = getLocationPictures($locationId, $dbConnection);

$dbConnection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location Pictures</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/location-style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    <style>
        button{
            background-color: rgb(8, 70, 8);
            color: white;
            font-size: 110%;
            padding: 0.5 1% 0.5% 1%;
            border-radius: 0.2rem;
            margin-bottom: 2rem;
        }
        .pictures-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(20rem, 1fr));
            gap: 1rem;
            justify-content: center;
            margin-top: 1rem;
        }

        .gallery-image {
            max-width: 300px;
            margin: 10px;
        }

        .gallery-image img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .no-pictures {
            text-align: center;
            font-size: 18px;
            color: #555;
        }
    </style>
</head>
<body>

<header>
    <?php include 'admin/admin-navbar.php'; ?>
</header>

<main>
    <h1>Location Pictures</h1>
    
    <?php if (!empty($locationPictures)): ?>
        <div class="pictures-gallery">
            <?php foreach ($locationPictures as $picture): ?>
                <div class="gallery-image">
                    <img src="<?php echo htmlspecialchars($picture); ?>" alt="Location Picture">
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="no-pictures">No pictures available for this location.</p>
    <?php endif; ?>
    <button class="view-pictures" onclick="window.location.href='view-location.php?location_id=<?php echo $locationId; ?>'">Back to View Location</button>

</main>

<footer>
    <?php include 'footer.php'; ?>
</footer>

</body>
</html>

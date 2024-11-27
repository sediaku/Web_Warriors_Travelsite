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
    <title>My Wishlist</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/wishlist.css">
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
    <h1>My Wishlist</h1>

    <?php
    if (!isset($_SESSION['user_id'])) {
        echo "<p>You must be logged in to view your wishlist.</p>";
        exit;
    }

    $userId = $_SESSION['user_id'];

    $query = "
        SELECT l.location_id AS location_id, l.location_name AS location_name
        FROM wishlist w
        INNER JOIN locations l ON w.location_id = l.location_id
        WHERE w.user_id = ?
    ";
    $stmt = $dbConnection->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table class = 'wishlist'>
                <thead>
                    <tr>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = $result->fetch_assoc()) {
            $locationId = $row['location_id'];
            $locationName = $row['location_name'];
            echo "
            <tr>
                <td>{$locationName}</td>
                <td>
                    <a href='view-location.php?location_id={$locationId}'>
                        <span class='material-symbols-outlined'>visibility</span>
                    </a>
                    <button class='delete-btn' data-location-id='{$locationId}'>
                        <span class='material-symbols-outlined'>delete</span>
                    </button>
                </td>
            </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>Your wishlist is empty.</p>";
    }

    $stmt->close();
    $dbConnection->close();
    ?>

    <script>
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                const locationId = this.getAttribute('data-location-id');

                if (confirm('Are you sure you want to remove this location from your wishlist?')) {
                    fetch('../functions/delete_wishlist.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ location_id: locationId })
                    })
                    .then(response => response.text())
                    .then(data => {
                        alert(data); 
                        location.reload(); 
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            });
        });
    </script>
</body>
</html>

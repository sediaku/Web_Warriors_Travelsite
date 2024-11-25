<?php
session_start();


if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    if($_SESSION['role'] ==1){
        include 'navbar_in.php'; 
    }else {
        include '../assets/admin-navbar.php'; 
    }
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist</title>
</head>
<body>
    <h1>My Wishlist</h1>

    <table>
        <thead>
            <tr>
                <th>Location</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            
            require_once 'db-config.php';

            
            session_start();

            
            if (!isset($_SESSION['user_id'])) {
                echo "<tr><td colspan='2'>You must be logged in to view your wishlist.</td></tr>";
                exit;
            }

            $userId = $_SESSION['user_id'];

            
            $query = "
                SELECT l.id AS location_id, l.name AS location_name
                FROM wishlist w
                INNER JOIN location l ON w.location_id = l.id
                WHERE w.user_id = ?
            ";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                
                while ($row = $result->fetch_assoc()) {
                    $locationId = $row['location_id'];
                    $locationName = $row['location_name'];
                    echo "
                    <tr>
                        <td>{$locationName}</td>
                        <td>
                            <a href='view-location.php?location_id={$locationId}'>View More</a>
                            <button class='delete-btn' data-location-id='{$locationId}'>Delete</button>
                        </td>
                    </tr>
                    ";
                }
            } else {
                echo "<tr><td>Your wishlist is empty.</td></tr>";
            }

            $stmt->close();
            $conn->close();
            ?>
        </tbody>
    </table>

    <script>
        
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                const locationId = this.getAttribute('data-location-id');

                if (confirm('Are you sure you want to remove this location from your wishlist?')) {
                    fetch('delete_wishlist.php', {
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

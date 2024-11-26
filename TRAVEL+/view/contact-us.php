<?php
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Contact Us</title>
        <meta name = "viewport" content = "width=device-width, initial-scale = 1">
        <link rel = "stylesheet" href = "../assets/css/style.css">
        <link rel = "stylesheet" href = "../assets/css/miscellaneous.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    </head>

    <body>
        <header>
        <?php 
            // Check if user is logged in and assign the appropriate navbar
            if (isset($_SESSION['user_id'])) {
                if ($_SESSION['role'] === 'admin') {
                    include '/admin/admin-navbar.php';  // For admin users
                } else {
                    include 'navbar_in.php';   // For normal logged-in users
                }
            } else {
                include 'navbar_guest.php';   // For logged-out users
            }
        ?>
        </header>

        <section class="contact-us">
            <h1>Our Team</h1>
            <p class = "heading">Dedication. Passion. Expertise</p>
            <p class = "heading">Meet the Team That is Bringing This Project to life</p>

            <div class = "individual">
                <div class = "image"><img src = "..\assets\images\Sedinam.jpg"></div>
                <div class = "description">
                    <h4>Project Manager</h4>
                    <h2>Sedinam Aku Senaya</h2>
                    <p>
                        Sedinam is a dedicated Computer Science student at Ashesi University, 
                        exploring AI, Data Science, and web development. In her free time, she enjoys comic books, anime, 
                        and engaging discussions about cartoons and the rich history of various countries, all while
                        embracing her identity as a bit of a church girly.
                    </p>

                    <p>
                        +233-50-547-9070 <br>
                        sedinam.senaya@ashesi.edu.gh
                    </p>
                </div>
            </div>

            <div class = "individual">
                <div class = "image"><img src = "..\assets\images\Nadia.jpg"></div>
                <div class = "description">
                    <h4>UI/UX Designer</h4>
                    <h2>Nadia Dodoo</h2>
                    <p>
                        Nadia is an aspiring game and website developer 
                        currently majoring in Computer Science at Ashesi University. 
                        When she isn't drowning in work, you can usually catch her reading or playing video games.
                    </p>

                    <p>
                        +233-50-817-2215 <br>
                        n.kirstyy@gmail.com
                    </p>
                </div>
            </div>

            <div class = "individual">
                <div class = "image"><img src = "..\assets\images\Georgina.jpg"></div>
                <div class = "description">
                    <h4>Front-End Developer</h4>
                    <h2>Georgina Yakoba Adjaye-Aggrey</h2>
                    <p>
                        Georgina is an entertainment lover of all genres(except boring ones) 
                        and is always happy to give back lovely and artistic designs to make clients feel good and smile. 
                        Just look for Georgina when you need someone who will let you get loose and crazy.
                    </p>

                    <p>
                        +233-57-330-5386<br>
                        georgina.adjaye@ashesi.edu.gh
                    </p>
                </div>
            </div>

            <div class = "individual">
                <div class = "image"><img src = "..\assets\images\Fridah.jpg"></div>
                <div class = "description">
                    <h4>Back-End Developer</h4>
                    <h2>Fridah Cheruto Cheboi</h2>
                    <p>
                        Fridah is a CS student with a passion for problem solving and creativity. 
                        She brings high levels of energy and a competitive drive to the drive. 
                    </p>

                    <p>
                        +254-75-912-9028<br>
                        fridah.cheboi@ashesi.edu.gh
                    </p>
                </div>
            </div>

        </section>

        <footer>
            <?php include 'footer.php'; ?>
        </footer>

    </body>
</html>
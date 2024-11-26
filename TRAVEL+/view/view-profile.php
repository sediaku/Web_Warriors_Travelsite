<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset="UTF-8">
        <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
        <title>Blog Post</title>  
        <link rel = "stylesheet" href = "../assets/css/style.css">
        <link rel = "stylesheet" href = "../assets/css/blog-style.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    </head>

    <body>
        <header>
            <?php include 'navbar_in.php'; ?>
        </header>

        <section class="profile-view">
            <div class = "left">
                <div id = "pfp">
                    <button class = "edit-btns">Edit</button>
                </div>

            </div>

            <div class = "right">
                <div class = "bio-section">
                    <h2>Bio</h2>
                    <p class = "bio">
                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem
                        accusantium doloremque laudantium, totam rem aperiam, 
                        eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. 
                        Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit
                        sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                    </p>
                    <button class = "edit-btns">Edit</button> 
                </div>

                <div class = "post-section">
                    <h2>Blog Posts</h2>
                </div>
            </div>

        </section>

        <footer>
            <?php include 'footer.php'; ?>
        </footer>

        <script src="../assets/js/blog.js"></script>
    </body>

    
</html>
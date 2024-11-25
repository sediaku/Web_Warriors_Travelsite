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
            <?php include '../navbar_guest.php'; ?>
        </header>

        <section class="blog-view">
            <div class = "left">
                <div class = "author"><a href="">Author Profile</a></div>
                <div class = "location"><a href="">Location Profile</a></div>
                <div class = "date">Date Posted</div>
            </div>

            <div class = "right">
                <div class = "Post">
                    "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, 
                    eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. 
                    Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                    Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, 
                    sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. 
                    Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? 
                    Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?"
                </div>
                <div class = "comment-section">
                    <h1>Comments</h1>
                    <div class = "comments"></div>
                    <button class="add-comment-btn">Add Comment</button>
                </div>
            </div>

        </section>
        <div id="commentModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="modal-header">
                    <h2>Add a Comment!</h2>
                </div>
                <form id="commentForm" class="comment-form">
                    <textarea 
                    class="comment-textarea"
                    placeholder="Type your comment here!"
                    required
                    ></textarea>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel">Cancel</button>
                        <button type="submit" class="btn btn-submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>

        <footer>
            <?php include 'footer.php'; ?>
        </footer>

        <script src="../assets/js/blog.js"></script>
    </body>

    
</html>
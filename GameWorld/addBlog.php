<?php
session_start();
include 'inc/functions.php';
$conn = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {    
    $title = $_POST['title'];
    $author = $_POST['author'];
    $content = $_POST['message'];
    $categoryId = $_POST['category_id'];
    

    if (!empty($title) && !empty($author) && !empty($content) && !empty($categoryId)) 
    {
        addBlogPost($title, $author, $content, $categoryId);
        echo "<p><strong>Blog post added successfully!</strong></p>";
    } else {
        echo "<p><strong>Please fill in all fields.</strong></p>";
    }

    header("Location: addBlog.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<?php ShowHead(); ?>

<body>
    <?php ShowHeader(); ?>
<div class="add-blog-container">
    <section class="add-blog">
        <h1>Add a New Blog Post</h1>
        <?php if (isset($_GET['success'])): ?>
            <p style="color: green;">Blog post successfully added!</p>
        <?php endif; ?>

        <form action="" method="post">
            

            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" required><br><br>

            <label for="author">Author:</label><br>
            <input type="text" id="author" name="author" required><br><br>

            <label for="category">Category:</label><br>
            <select id="category" name="category_id" required>
                <option value="">Select a category</option>
                <option value="1">New Products</option>
                <option value="2">Game Reviews</option>
                <option value="3">Console Reviews</option>
            </select><br><br>


            <label for="message">Blog post:</label><br>
            <textarea id="message" name="message" rows="5" required></textarea><br><br>

            <button type="submit">Submit</button>
        </form>

        
</section>
    <section class="blog-categories">
            <h2>Blog Categories</h2>
            <ul>
                <li><a href="blog.php?category=News">New Products</a></li>
                <li><a href="blog.php?category=Reviews">Game Reviews</a></li>
                <li><a href="blog.php?category=Guides">Console Reviews</a></li>
            </ul>
        </section>
</div>

</body>
<?php ShowFooter(); ?>

</html>
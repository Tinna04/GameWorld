<?php
session_start();
include 'inc/functions.php';

if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
    $categoryId = (int)$_GET['category_id'];
    $blogPosts = getBlogPostsByCategory($categoryId);
} else {
    $blogPosts = getAllBlogPosts();
}
?>
<!DOCTYPE html>
<html lang="en">
<?php ShowHead(); ?>

<body>
    <?php ShowHeader(); ?>
    <div class="blog-container">

        <section class="blog-posts">
            <h1>Blog Posts</h1>
            <?php if ($blogPosts && $blogPosts->num_rows > 0): ?>
                <?php while ($post = $blogPosts->fetch_assoc()): ?>
                    <div class="blog-post">
                        <h2><?= $post['title'] ?></h2>
                        <p class="meta">
                            By <?= $post['author'] ?> |
                            <?= date("d-m-Y", strtotime($post['created_at'])); ?>
                        </p>
                        <p>
                            <?= substr($post['content'], 0, 150) ?>...
                        </p>
                        <a href="blogDetails.php?id=<?= $post['id'] ?>">
                            Read more
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No blog posts found.</p>
            <?php endif; ?>
        </section>
        
        <aside class="blog-sidebar">
            <a href="addBlog.php">Add New Post</a>
            <h2>Categories</h2>
            <ul>
                <li><a href="blog.php?category_id=1">New Products</a></li>
                <li><a href="blog.php?category_id=2">Game Reviews</a></li>
                <li><a href="blog.php?category_id=3">Console Reviews</a></li>
                <li><a href="blog.php">All Posts</a></li>
            </ul>
        </aside>

    </div>

    <?php ShowFooter(); ?>
</body>

</html>
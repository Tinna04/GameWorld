<?php
session_start();
include 'inc/functions.php';

// check of id geldig is
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid blog post.");
}

$postId = (int)$_GET['id'];
$post = getBlogPostById($postId);

if (!$post) {
    die("Blog post not found.");
}

// adding comments 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $comment = $_POST['comment'];

    if (!empty($name) && !empty($comment)) {
        addComment($postId, $name, $comment);
        // redirect om dubbel toevoegen bij refresh te voorkomen
        header("Location: blogDetails.php?id=" . $postId);
        exit();
    }
}

// deleting blogpost (admin only)
if (isset($_POST['delete_blog'])) {
    deleteBlogPost($postId);
    // redirect om dubbel verwijderen bij refresh te voorkomen
    header("Location: blog.php");
    exit();
}

// deleting comments (admin only)
if (isset($_POST['delete_comment'])) {
    $commentId = (int)$_POST['comment_id'];

    deleteComment($commentId);
    // redirect om dubbel verwijderen bij refresh te voorkomen
    header("Location: blogDetails.php?id=" . $postId);
    exit();
}

$comments = getCommentsByBlogId($postId);
?>

<!DOCTYPE html>
<html lang="en">

<?php ShowHead(); ?>
<body>
    <?php ShowHeader(); ?>
    <section class="blog-details">
        <h1><?= $post['title']; ?></h1>

        <!-- <form method="post">
            <button type="submit" name="delete_blog">Delete Blog post</button>
        </form> -->

        <p class="meta">
            By <?= $post['author'] ?> |
            <?= date("d-m-Y", strtotime($post['created_at'])); ?>
        </p>
        <div class="content">
            <P><?= nl2br($post['content']); ?></P>
        </div>
    </section>

    <section class="comments">
        <h2>Comments</h2>
        <?php if ($comments && $comments->num_rows > 0): ?>

            <?php while ($comment = $comments->fetch_assoc()): ?>

                <div class="comment">
                    <strong><?= htmlspecialchars($comment['name']); ?></strong>
                    <small><?= date("d-m-Y H:i", strtotime($comment['created_at'])); ?></small>
                    <p><?= nl2br(htmlspecialchars($comment['comment'])); ?></p>

                    <!-- DELETE BUTTON -->
                    <!-- <form method="post" style="display:inline;">
                        <input type="hidden" name="comment_id" value="<?= $comment['id']; ?>">
                        <button type="submit" name="delete_comment">Delete</button>
                    </form> -->
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No comments yet.</p>
        <?php endif; ?>

    </section>

    <!-- Comment form -->
    <section class="comment-form">
        <h2>Leave a Comment</h2>
        <form method="post">
        <input type="text" name="name" placeholder="Your name" required><br><br>

        <textarea name="comment" placeholder="Your comment" required></textarea><br><br>

        <button type="submit">Post Comment</button>
    </form>
    </section>

<?php ShowFooter(); ?>
</body>
</html>
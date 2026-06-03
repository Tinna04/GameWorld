<?php
session_start();
include 'inc/functions.php';
$conn = getDatabaseConnection();

$submitted = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    $submitted = true;
}
?>

<!DOCTYPE html>
<html lang="en">

<?php ShowHead(); ?>

<body>
    <?php ShowHeader(); ?>
    <div class="contact">
        <h1>Contact Us</h1>

        <div class="summary">
        <?php if ($submitted): ?>
            <p>Thank you, <?= htmlspecialchars($name); ?>! Your message has been received. We will get back to you at <?= htmlspecialchars($email); ?>. Here is a summary of your message:</p>
            <p><?= htmlspecialchars($message); ?></p>
        <?php endif; ?>
        </div>

        <p>If you have any questions or need assistance, please fill out the form below:</p>

        <form action="" method="post">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>

            <label for="message">Message:</label><br>
            <textarea id="message" name="message" rows="5" required></textarea><br><br>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>

<?php ShowFooter(); ?>

</html>
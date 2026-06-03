<?php
session_start();
include 'inc/functions.php';

$about = getAboutPage();
?>

<!DOCTYPE html>
<html lang="en">

<?php ShowHead(); ?>

<body>
    <?php ShowHeader(); ?>
    <section class="container-about">
    <div class="about">
        <h1><?= $about['title']; ?></h1>
        <p>
            <?= nl2br($about['content']); ?>
        </p>
    </div>
    <aside>
        <img class="about-img"
            src="<?= $about['image']; ?>"
            alt="<?= $about['title']; ?>"
            width="555">
    </aside>
    </section>
</body>

<?php ShowFooter(); ?>

</html>
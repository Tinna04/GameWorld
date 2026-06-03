<?php
session_start();
include 'inc/functions.php';

$popularProducts = getPopularProducts();

// Add single product to cart
if (isset($_POST['add_single'])) {
    $productId = (int) $_POST['add_single'];
    $product = getProductById($productId);

    if ($product) {
        addToCart(
            $product['id'],
            $product['name'],
            $product['price']
        );
    }

    header("Location: index.php");
    exit();
}

// add multiple products
if (isset($_POST['add_selected']) && !empty($_POST['products'])) {
    foreach ($_POST['products'] as $productId) {
        $product = getProductById($productId);
        if ($product) {
            addToCart(
                $product['id'],
                $product['name'],
                $product['price']
            );
        }
    }

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<?php ShowHead(); ?>

<body>
    <?php ShowHeader(); ?>

    <section class="welcome">
        <h1> Welcome to GameWorld </h1>
        <p> Discover the best games for Playstation Xbox and PC </p>
    </section>

    <section class="Container">
        <div class="popular-games">
            <h2>Most Popular</h2>
            <form method="post">
                <main class="products">
                    <?php if ($popularProducts && $popularProducts->num_rows > 0): ?>
                        <?php while ($product = $popularProducts->fetch_assoc()): ?>
                            <div class="product">
                                <a href="productDetails.php?productId=<?= $product['id']; ?>">
                                    <img src="img/games/<?= $product['image']; ?>" alt="<?= $product['name']; ?>" width="150">
                                </a>
                                <h3>
                                    <a href="productDetails.php?productId=<?= $product['id']; ?>">
                                        <?= $product['name']; ?>
                                    </a>
                                </h3>
                                <p>€<?= number_format($product['price'], 2); ?></p>

                                <input type="checkbox" name="products[]" value="<?= $product['id']; ?>">

                                <button type="submit" name="add_single" value="<?= $product['id']; ?>" class="add-to-cart-btn">Add to cart</button>
                                <button type="submit" name="add_selected" class="add-to-cart-btn">Add Selected to Cart</button>

            </form>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No popular products found.</p>
<?php endif; ?>
</main>
</div>
<aside class="categories">
    <h1>Choose your category</h1>

    <div class="category-list">

        <a href="products.php?categoryId=1" class="category-playstation">

            <h2>PlayStation</h2>
        </a>

        <a href="products.php?categoryId=2" class="category-xbox">

            <h2>Xbox</h2>
        </a>

        <a href="products.php?categoryId=3" class="category-pc">

            <h2>PC</h2>
        </a>
    </div>
</aside>
    </section>
</body>

<?php ShowFooter(); ?>
<?php
session_start();
include 'inc/functions.php';

// check of categoryId bestaat en geldig is
if (!isValidCategory()) {
    die("Invalid category");
}

$categoryId = (int) $_GET['categoryId'];
$categoryName = getCategoryName($categoryId);

if ($categoryName === null) {
    die("Category not found");
}

$productResult = getProductByCategory($categoryId);

// add to cart afhandeling
if (isset($_POST['add_single'])){
    $productId = (int) $_POST['add_single'];
    $product = getProductById($productId);

    if ($product) {
        addToCart(
            $product['id'],
            $product['name'],
            $product['price']
        );
    }
    header("Location: products.php?categoryId=" . $categoryId);
    exit();
}

if (isset($_POST['add_selected']) && !empty($_POST['products'])) {

    foreach ($_POST['products'] as $productId) {
        // product ophalen uit database (veilig!)
        $product = getProductById($productId);
        if ($product) {
            addToCart(
                $product['id'],
                $product['name'],
                $product['price']
            );
        }
    }
    header("Location: products.php?categoryId=" . $categoryId);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<?php ShowHead(); ?>

<body>
    <?php ShowHeader(); ?>

    <section class="category-<?= $categoryId; ?>">
        <h1><?= $categoryName; ?></h1>

        <form method="post">
            <div class="products">
                <?php if ($productResult->num_rows > 0): ?>
                    <?php while ($product = $productResult->fetch_assoc()): ?>
                        <div class="product">
                            <a href="productDetails.php?productId=<?= $product['id']; ?>">
                                <img src="img/games/<?= $product['image']; ?>" alt="<?= $product['name']; ?>">
                            </a>
                            <h3><a href="productDetails.php?productId=<?= $product['id']; ?>">
                                    <?= $product['name']; ?>
                                </a></h3>
                            <p>€<?= number_format($product['price'], 2); ?></p>


                            <input type="checkbox" name="products[]" value="<?= $product['id']; ?>">

                            <button type="submit" name="add_single" value="<?= $product['id']; ?>" class="add-to-cart-btn">Add to cart</button>
                            <button type="submit" name="add_selected" class="add-to-cart-btn">Add Selected to Cart</button>
                        </div>
                        
        </form>
    <?php endwhile; ?>
<?php else: ?>
    <p>No products found.</p>
<?php endif; ?>
</div>
    </section>
    <section class="categories">
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
    </section>


    <?php ShowFooter(); ?>
</body>

</html>
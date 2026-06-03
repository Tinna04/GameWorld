<?php
session_start();
include 'inc/functions.php';

// check of productId geldig is
if (!isset($_GET['productId']) || !is_numeric($_GET['productId'])) {
    die("Invalid product.");
}

$productId = (int)$_GET['productId'];
$product = getProductById($productId);

if (!$product) {
    die("Product not found.");
}

// add to cart afhandeling
if (isset($_POST['add_to_cart'])) {

    $productId = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    // product ophalen uit database (veilig!)
    $product = getProductById($productId);

    if ($product) {

        // meerdere keren toevoegen op basis van quantity
        for ($i = 0; $i < $quantity; $i++) {
            addToCart(
                $product['id'],
                $product['name'],
                $product['price']
            );
        }

        // redirect om dubbel toevoegen bij refresh te voorkomen
        header("Location: productDetails.php?productId=" . $productId);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<?php ShowHead(); ?>

<body>
    <?php ShowHeader(); ?>

    <section class="product-details">
        <h1><?= $product['name']; ?></h1>

        <img src="img/games/<?= $product['image']; ?>"
            alt="<?= $product['name']; ?>"
            width="300">

        <div class="product-description">
            <p><?= $product['details']; ?></p>
        </div>

        <div class="download-links">
            <?php if (!empty($product['download_file'])): ?>
            <a href="<?= $product['download_file']; ?>" download class="download-btn">
                Download Game
            </a>
            <?php endif; ?>
        </div>

        <p><strong>Price: €<?= number_format($product['price'], 2); ?></strong></p>

        <form method="post">
            <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
            <input type="hidden" name="product_name" value="<?= $product['name']; ?>">
            <input type="hidden" name="product_price" value="<?= $product['price']; ?>">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="1" min="1">
            <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>
    </section>

    <?php ShowFooter(); ?>
</body>

</html>
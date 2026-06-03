<?php
session_start();
include 'inc/functions.php';
$conn = getDatabaseConnection();
$total = 0;

// ----------
// REMOVE ITEM FROM CART
// ----------
if (isset($_POST['remove_one'])) {
    $productId = $_POST['productId'];

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity']--;

        // Als quantity 0 of minder is, verwijder het item uit de cart
        if ($_SESSION['cart'][$productId]['quantity'] <= 0) {
            unset($_SESSION['cart'][$productId]);
        }
    }
}

// ----------
// CHECKOUT BUTTON (later uitbreiden) 
// ----------
if (isset($_POST['checkout'])) {
    // Later: hier komt IDEAL of PayPal integratie
    echo "<p><strong>Checkout functionality will be implemented soon!</strong></p>";
}
?>

<?php ShowHead(); ?>

<body>
    <?php ShowHeader(); ?>
    <section class="checkout-container">
    <h1>Shopping Cart</h1>

    <div class="checkout">
        <?php if (!empty($_SESSION['cart'])): ?>
            <table>
                <tr>
                    <th>Game</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>

                <?php foreach ($_SESSION['cart'] as $item):
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                ?>

                    <tr>
                        <td><?= $item['name']; ?></td>
                        <td><?= number_format($item['price'], 2); ?></td>
                        <td><?= $item['quantity']; ?></td>
                        <td><?= number_format($subtotal, 2); ?></td>

                        <td>
                            <form method="post">
                                <input type="hidden" name="productId" value="<?= $item['id']; ?>">
                                <button type="submit" name="remove_one" class="remove-btn"> - </button>
                            </form>
                    </tr>
                <?php endforeach; ?>

                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    </td>
                    <td><strong><?= number_format($total, 2); ?></strong></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <form method="post">
                            <button type="submit" name="checkout" class="checkout-btn">Checkout</button>
                        </form>
                    </td>
                </tr>
            </table>
            
            
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
    </section>
</body>

<?php ShowFooter(); ?>
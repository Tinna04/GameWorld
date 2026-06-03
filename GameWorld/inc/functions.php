<?php
//--------------------------------
// Database connection
//--------------------------------
function getDatabaseConnection()
{
    if ($_SERVER['HTTP_HOST'] === 'localhost') 
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "gameworld";
    } 
    else
    {
        $servername = "localhost";
        $username = "st1738846558";
        $password = "yPO8e4u4bEBdx2J";
        $dbname = "st1738846558";
    }

    $conn = new mysqli($servername, $username, $password, $dbname);

    // check for errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

//
//
//
function getPopularProducts()
{
    $conn = getDatabaseConnection();

    $sql = "SELECT * FROM products WHERE popular = 1";
    $result = $conn->query($sql);

    return $result;
}

function getProductById($productId)
{
    $conn = getDatabaseConnection();

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

function getAboutPage()
{
    $conn = getDatabaseConnection();

    $sql = "SELECT * FROM about LIMIT 1";
    $result = $conn->query($sql);

    return $result->fetch_assoc();
}

//---------------------------------
// Shopping cart
//---------------------------------
function addToCart($id, $name, $price)
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity']++;
    } else {
        $_SESSION['cart'][$id] = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'quantity' => 1
        ];
    }
}

function getCartCount()
{
    if(!isset($_SESSION['cart']) || !is_array(($_SESSION['cart'])))
        {
            return 0;
        }

    $count = 0;

    
    foreach ($_SESSION['cart'] as $item) 
    {
        $count += $item['quantity'];
    }
    

    return $count;
}

//---------------------------------
// Category & product functions
//---------------------------------
function getCategoryName($categoryId)
{
    $conn = getDatabaseConnection();

    $stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return null;
    }

    $row = $result->fetch_assoc();
    return $row['name'];
}

function getProductByCategory($categoryId)
{
    $conn = getDatabaseConnection();

    // Category 4 = All products
    if ($categoryId == 4) {
        $sql = "SELECT * FROM products";
        return $conn->query($sql);
    }

    $stmt = $conn->prepare
            ("SELECT * FROM products WHERE category_id = ?");
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();

    return $stmt->get_result();
}

function isValidCategory()
{
    if (!isset($_GET['categoryId']) || !is_numeric($_GET['categoryId'])) {
        return false;
    }
    
    $categoryId = (int) $_GET['categoryId'];

    // Sta categorie 1 t/m 4 toe
    return $categoryId >= 1 && $categoryId <= 4;
}

//---------------------------------
// Blog functions
//---------------------------------
function addBlogPost($title, $author, $content, $categoryId)
{
    $conn = getDatabaseConnection();

    $stmt = $conn->prepare("
    INSERT INTO blog_posts (title, author, content, category_id, created_at) 
    VALUES (?, ?, ?, ?, NOW())");

    $stmt->bind_param("sssi", $title, $author, $content, $categoryId);

    return $stmt->execute();
}

function getBlogPostsByCategory($categoryId)
{
    $conn = getDatabaseConnection();

    $stmt = $conn->prepare("
        SELECT * FROM blog_posts 
        WHERE category_id = ?
        ORDER BY created_at DESC
    ");

    $stmt->bind_param("i", $categoryId);
    $stmt->execute();

    return $stmt->get_result();
}

function getBlogPostById($postId)
{
    $conn = getDatabaseConnection();

    $stmt = $conn->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->bind_param("i", $postId);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

function getAllBlogPosts()
{
    $conn = getDatabaseConnection();

    $sql = "SELECT * FROM blog_posts ORDER BY created_at DESC";
    return $conn->query($sql);
}

function deleteBlogPost($postId)
{
    $conn = getDatabaseConnection();

    // delete comments first (otherwise foreign key constraint error)
    $stmt1 = $conn->prepare("DELETE FROM blog_comments WHERE blog_id = ?");
    $stmt1->bind_param("i", $postId);
    $stmt1->execute();

    // delete the blog post
    $stmt2 = $conn->prepare("DELETE FROM blog_posts WHERE id = ?");
    $stmt2->bind_param("i", $postId);

    return $stmt2->execute();
}

//---------------------------------
// Blog comment functions
//---------------------------------
function addComment($blogId, $name, $comment)
{
    $conn = getDatabaseConnection();

    $stmt = $conn->prepare("
    INSERT INTO blog_comments (blog_id, name, comment, created_at) 
    VALUES (?, ?, ?, NOW())");

    $stmt->bind_param("iss", $blogId, $name, $comment);

    return $stmt->execute();
}

function getCommentsByBlogId($blogId)
{
    $conn = getDatabaseConnection();

    $stmt = $conn->prepare("
        SELECT * FROM blog_comments 
        WHERE blog_id = ?
        ORDER BY created_at DESC
    ");

    $stmt->bind_param("i", $blogId);
    $stmt->execute();

    return $stmt->get_result();
}

function deleteComment($commentId)
{
    $conn = getDatabaseConnection();

    $stmt = $conn->prepare("DELETE FROM blog_comments WHERE id = ?");
    $stmt->bind_param("i", $commentId);

    return $stmt->execute();
}

//---------------------------------
// HTML functions
//---------------------------------
function ShowHead()
{
?>

    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Tijn van Gils">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>GameWorld</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
<?php
}

function ShowHeader()
{
?>
    <header>
        <div id="logo">
            <a class="logo-link" href="index.php">
                <img src="img/game-world-logo.png" alt="logo" width=150>
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php?categoryId=4">Products</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="about.php">About us</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
        <?php
        $cartCount = getCartCount();
        ?>
        <a class="cart-link" href="checkout.php">
            <img src="img/winkelwagentje.png" alt="cart" width=150>
            <?= $cartCount; ?>
        </a>
    </header>
<?php
}

function ShowFooter()
{
?>
    <footer>
        <p>&copy; 2025 GameWorld. All rights reserved.</p>
    </footer>
    </body>

    </html>
<?php
}
?>
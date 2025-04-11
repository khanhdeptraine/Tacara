<?php
session_start();
include 'connect.php';

// Xóa sản phẩm khỏi giỏ hàng nếu có yêu cầu remove
if (isset($_GET['remove'])) {
    $product_id_to_remove = $_GET['remove'];

    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['product_id'] == $product_id_to_remove) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // reset lại index
            break;
        }
    }
}

// Kiểm tra nếu có yêu cầu cập nhật số lượng
if (isset($_POST['update_qty']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];

    // Kiểm tra xem số lượng có hợp lệ (lớn hơn 0)
    if ($new_quantity > 0) {
        // Cập nhật số lượng sản phẩm trong giỏ hàng
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $product_id) {
                $item['quantity'] = $new_quantity;
                break;
            }
        }
    }
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/test.css">
    <link rel="stylesheet" href="css/cart.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <title>TACARA</title>
    
</head>
<body>
    <header class="header-container">
        <div class="container py-3">
            <nav class="d-flex">
                <a href="" >VIETNAMESE</a>
                <a href="contactus.php" >CONTACT US</a>
                <a href="service.php" >SERVICES</a>
            </nav>
            <nav class="map">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-profile">
                        <span class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        <a href="profile.php?user_id=<?= urlencode($_SESSION['user_id']) ?>" class="profile-user">
                            <img src="img/user.png" alt="">
                        </a>
                    </div>
                <?php else: ?>
                    <a href="register.php"><img src="img/user.png" alt="User"></a>
                <?php endif; ?>
                <a href="wishlist.php"><img src="img/favorite.png" alt=""></a>
                <a href="map.php"><img src="img/placeholder.png" alt=""></a>
                <a href="cart.php?user_id=<?= urlencode($_SESSION['user_id'] ?? '') ?>">
                    <img src="img/parcel.png" alt="Cart">
                </a>
                <div class="dash"></div>
                <div class="search-container">
                    <img src="img/search.png" alt="Search" class="search-icon">
                </div>
                <div class="search-modal">
                    <div class="search-box">
                        <span class="close-btn">&times;</span>
                        <h2 class="title-search">Search</h2>
                        <form class="form-search" action="search.php" method="GET">
                            <div class="form-input-2">
                                <input type="text"  name="query" placeholder="Search for products..." required>
                                <button type="submit">Search</button>
                            </div>
                        </form>
                    </div>
                </div>  
            </nav>
        </div>
        <div class="logo">
            <a href="home.php" > <img src="img/image.png" alt=""></a>
        </div>
        <nav class="nav-title">
            <?php
                $categories = $conn->query("SELECT id, name FROM categories");
                while ($category = $categories->fetch_assoc()) {
                    echo '<div class="dropdown">';
                    echo '<a href="naturalshop.php?category_id=' . urlencode($category['id']) . '" class="dropdown-toggle">' . htmlspecialchars($category['name']) . '</a>';
                    $subcategories = $conn->query("SELECT id, name FROM subcategories WHERE category_id = " . $category['id']);
                    if ($subcategories->num_rows > 0) {
                        echo '<div class="dropdown-menu"><div class="sub-dropdown">';
                        while ($sub = $subcategories->fetch_assoc()) {
                            echo '<a href="shop_id.php?subcategory_id=' . urlencode($sub['id']) . '&user_id=' . urlencode($_SESSION['user_id'] ?? '') . '">' . htmlspecialchars($sub['name']) . '</a>';
                        }
                        echo '</div></div>';
                    }
                    echo '</div>';
                }
            ?>
        </nav>
    </header>

    <!-- MINI CART -->
    <?php if (!empty($_SESSION['cart'])): ?>
    <div id="mini-cart" class="mini-cart">
        <div class="mini-cart-header">
            <h5>🛍️ Giỏ hàng</h5>
        </div>
        <div class="mini-cart-content">
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <div class="mini-cart-item">
                    <img src="uploads/<?= htmlspecialchars($item['image'] ?? 'default.jpg') ?>" alt="">
                    <div class="mini-cart-details">
                        <p><?= htmlspecialchars($item['title'] ?? 'Không có tên') ?></p>
                        <small><?= $item['quantity'] ?> x <?= number_format($item['price'], 0, ',', '.') ?> VND</small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="mini-cart-footer">
            <a href="cart.php" class="btn btn-primary btn-sm w-100">Xem giỏ hàng</a>
        </div>
    </div>
    <?php endif; ?>

<script>
    const cartLink = document.querySelector("a[href*='cart.php']");
    const miniCart = document.getElementById("mini-cart");

    if (cartLink && miniCart) {
        cartLink.addEventListener("mouseenter", () => {
            miniCart.classList.add("open");
        });

        miniCart.addEventListener("mouseleave", () => {
            miniCart.classList.remove("open");
        });

        cartLink.addEventListener("mouseleave", () => {
            setTimeout(() => {
                if (!miniCart.matches(':hover')) {
                    miniCart.classList.remove("open");
                }
            }, 300);
        });
    }
</script>
</body>
</html>

<?php
session_start(); 
include 'connect.php';

$search = $_GET['query'] ?? '';

$sql = "SELECT id, image, title, title2, price, subcategory_id FROM products WHERE title LIKE '%$search%' OR title2 LIKE '%$search%'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/test.css">
    <link rel="stylesheet" href="css/natural.css">
    <title>Search Results - TACARA</title>
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
                $cat_conn = new mysqli("localhost", "root", "", "tacara");
                $categories = $cat_conn->query("SELECT id, name FROM categories");
                while ($category = $categories->fetch_assoc()) {
                    echo '<div class="dropdown">';
                    echo '<a href="naturalshop.php?category_id=' . urlencode($category['id']) . '" class="dropdown-toggle">' . htmlspecialchars($category['name']) . '</a>';

                    $subcategories = $cat_conn->query("SELECT id, name FROM subcategories WHERE category_id = " . $category['id']);
                    if ($subcategories->num_rows > 0) {
                        echo '<div class="dropdown-menu"><div class="sub-dropdown">';
                        while ($sub = $subcategories->fetch_assoc()) {
                            echo '<a href="shop_id.php?subcategory_id=' . urlencode($sub['id']) . '&user_id=' . urlencode($_SESSION['user_id'] ?? '') . '">' . htmlspecialchars($sub['name']) . '</a>';
                        }
                        echo '</div></div>';
                    }
                    echo '</div>';
                }
                $cat_conn->close();
            ?>
        </nav>

    </header>


    <div class="line-natural"></div>
    <div class="container-title">
        <img src="img/titlenatural.png" alt="">
        <h1>Search Results for: <?= htmlspecialchars($search) ?></h1>
    </div>
    <div class="breadcrumb">
        <a href="home.php">HOME</a>
        <span>/</span>
        <a href="#">Search</a>
    </div>
    <div class="container-products">
        <div class="filter-section">
            <div class="sort-by">
                <h3>Sort By</h3>
                <label><input type="radio" name="sort" value="low-high"> Price Low To High</label>
                <label><input type="radio" name="sort" value="high-low"> Price High to Low</label>
                <label><input type="radio" name="sort" value="recommended"> Recommended</label>
            </div>
        </div>
        <div class="products-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $subcategory_id = $row['subcategory_id'];
                $size_sql = "SELECT size FROM size_types WHERE subcategory_id = $subcategory_id";
                $size_result = $conn->query($size_sql);
                $sizes = [];
                while ($size_row = $size_result->fetch_assoc()) {
                    $sizes[] = $size_row['size'];
                }
        ?>
         <div class="natural-products">
                    <div class="product-wrapper">
                        <button class="wishlist-btn" data-product-id="<?= $row['id'] ?>">
                            <img src="img/heart.png" alt="Wishlist">
                        </button>
                        <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="Sản phẩm" class="product-image">
                    </div>
                    <div class="natural-title-product"><?= htmlspecialchars($row['title']) ?></div>
                    <div class="natural-title-product-2"><?= htmlspecialchars($row['title2']) ?></div>
                    <div class="price-product"><?= number_format($row['price'], 0, ',', '.') ?> VND</div>
                    <button class="select-button">SELECT SIZE</button>  
                    <div class="size-selection" style="display: none;">
                        <select class="size-dropdown">
                            <option value="">Select Size</option>
                            <?php foreach ($sizes as $size) { ?>
                                <option value="<?= $size ?>"><?= $size ?></option>
                            <?php } ?>
                        </select>
                        <button class="add-to-bag" data-product-id="<?= $row['id'] ?>">ADD TO BAG</button>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p>No products found!</p>";
        }
        $conn->close();
        ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function () {
        $(".select-button").on("click", function () {
            let parent = $(this).closest(".natural-products");
            let sizeSelection = parent.find(".size-selection");

            $(".size-selection").not(sizeSelection).slideUp();
            sizeSelection.slideToggle();
        });

        $(".add-to-bag").on("click", function () {
            let product_id = $(this).data("product-id");
            let parent = $(this).closest(".natural-products");
            let size = parent.find(".size-dropdown").val();
            let user_id = "<?= $_SESSION['user_id'] ?? '' ?>";

            if (!size) {
                alert("Vui lòng chọn kích thước!");
                return;
            }

            $.post("add_to_cart.php", { product_id: product_id, size: size, user_id: user_id })
                .done(function (response) {
                    if (response.includes("Bạn cần đăng nhập")) {
                        alert("Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.");
                    } else {
                        alert("Sản phẩm đã được thêm vào giỏ hàng!");
                        console.log(response);
                    }
                })
                .fail(function (xhr, status, error) {
                    console.error("Lỗi:", error);
                });
        });

        $(".wishlist-btn, .add-to-wishlist").on("click", function () {
            let product_id = $(this).data("product-id");

            $.post("add_to_wishlist.php", { product_id: product_id })
                .done(function (response) {
                    console.log(response); 
                })
                .fail(function (xhr, status, error) {
                    console.error("Lỗi:", error);
                });
        });
    });

    $(document).ready(function () {
  
    $("input[name='sort']").on("change", function () {
        fetchProducts();
    });

 
    function fetchProducts() {
        let sort = $("input[name='sort']:checked").val(); 
        let filters = []; 
        
  
        $.post("fetch-products.php", { sort: sort, filters: filters })
            .done(function (response) {
                $(".products-container").html(response); 
            })
            .fail(function (xhr, status, error) {
                console.error("Error fetching products:", error);
            });
    }

 

});


    </script>
</body>
</html>

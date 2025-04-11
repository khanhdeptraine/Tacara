<?php
session_start(); 
$category_id = $_GET['category_id'] ?? null;
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
                include 'connect.php';
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
                $conn->close();
            ?>
        </nav>
    </header>



    <div class="line-natural"></div>
    <div class="container-title">
        <img src="img/titlenatural.png" alt="">
        <h1>ALL CREATIONS</h1>
    </div>
    <div class="breadcrumb">
        <a href="home.php">HOME</a>
        <span>/</span>
        <a href="">NATURAL DIAMOND JEWELRY</a>
        <span>/</span>
        <a href="naturalshop.php">ALL CREATIONS</a>
    </div>
    <div class="container-products">
    <div class="filter-section">
            <div class="sort-by">
                <h3>Sort By</h3>
                <label><input type="radio" name="sort" value="low-high"> Price Low To High</label>
                <label><input type="radio" name="sort" value="high-low"> Price High to Low</label>
                <label><input type="radio" name="sort" value="recommended"> Recommended</label>
            </div>
        <div class="group-natural">
            <h5 class="filter-heading" data-category-range="1-5">Natural Diamond Jewelry</h5>
            <label><input type="checkbox" name="category[]" value="1"> Nhẫn nữ & nam</label>
            <label><input type="checkbox" name="category[]" value="2"> Bông tai</label>
            <label><input type="checkbox" name="category[]" value="3"> Mặt & Dây chuyền</label>
            <label><input type="checkbox" name="category[]" value="4"> Lắc & Vòng tay</label>
            <label><input type="checkbox" name="category[]" value="5"> Bộ trang sức</label>
        </div>

        
        <div class="group-lab-grown">
            <h5 class="filter-heading" data-category-range="6-10">Lab Grown Jewelry</h5>
            <label><input type="checkbox" name="category[]" value="6"> Nhẫn nữ & nam</label>
            <label><input type="checkbox" name="category[]" value="7"> Bông tai</label>
            <label><input type="checkbox" name="category[]" value="8"> Mặt & Dây chuyền</label>
            <label><input type="checkbox" name="category[]" value="9"> Lắc & Vòng tay</label>
            <label><input type="checkbox" name="category[]" value="10"> Bộ trang sức</label>
        </div>

        
        <div class="group-other">
            <h5 class="filter-heading" data-category-range="11-12">TAN'LOVE</h5>
            <label><input type="checkbox" name="category[]" value="11"> Nhẫn yêu và cưới</label>
            <label><input type="checkbox" name="category[]" value="12"> Nhẫn cầu hôn</label>
        </div>
    </div>

        <div class="products-container">
    <?php
    include 'connect.php';

    $category_id = $_GET['category_id'] ?? null;

    if ($category_id) {
        $sql = "SELECT p.id, p.image, p.title, p.title2, p.price, p.subcategory_id
                FROM products p
                INNER JOIN subcategories s ON p.subcategory_id = s.id
                WHERE s.category_id = " . intval($category_id);
    } else {
        $sql = "SELECT id, image, title, title2, price, subcategory_id FROM products";
    }

    $result = $conn->query($sql);
    
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
        echo "<p>Không có sản phẩm nào!</p>";
    }
    $conn->close();
    ?>
</div>

    </div>   
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
          
          let category_id = "<?= $category_id ?>";

    if (category_id) {
        category_id = parseInt(category_id);

    let targetHeading = null;

    if (category_id === 1) {
        targetHeading = $(".filter-heading[data-category-range='1-5']");
    } else if (category_id === 2) {
        targetHeading = $(".filter-heading[data-category-range='6-10']");
    } else if (category_id === 3) {
        targetHeading = $(".filter-heading[data-category-range='11-12']");
    }

    if (targetHeading) {
        targetHeading.trigger("click");
    }
}
$(`.filter-heading[data-category-id="${category_id}"]`).trigger("click");


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
    let user_id = "<?= $_SESSION['user_id'] ?? '' ?>"; // Lấy user_id từ session

    if (!size) {
        alert("Vui lòng chọn kích thước!");
        return;
    }

    // Gửi dữ liệu đến server để xử lý việc thêm vào giỏ hàng
    $.post("add_to_cart.php", { product_id: product_id, size: size, user_id: user_id })
        .done(function (response) {
            if (response.includes("Bạn cần đăng nhập")) {
                alert("Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.");
            } else {
                // Thông báo sản phẩm đã được thêm vào giỏ hàng
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

function fetchProducts() {
    let sort = $("input[name='sort']:checked").val();
    let filters = [];

    $(".filter-section input[type='checkbox']:checked").each(function () {
        filters.push($(this).val());
    });

    $.post("fetch-products.php", { 
        sort: sort, 
        filters: filters, 
        category_id: category_id 
    })
    .done(function (response) {
        $(".products-container").html(response);
    });
}



$(".filter-heading").on("click", function () {
    let range = $(this).data("category-range").split("-");
    let start = parseInt(range[0]);
    let end = parseInt(range[1]);

    // Bỏ chọn tất cả checkbox trước (tuỳ bạn muốn giữ hay bỏ)
    $("input[name='category[]']").prop("checked", false);

    // Chọn các checkbox thuộc nhóm đó
    $("input[name='category[]']").each(function () {
        let val = parseInt($(this).val());
        if (val >= start && val <= end) {
            $(this).prop("checked", true);
        }
    });

    // Gọi lại hàm fetchProducts sau khi thay đổi checkbox
    fetchProducts();
});

$("input[type='radio'], input[type='checkbox']").on("change", fetchProducts);
fetchProducts(); 


$(".search-icon").on("click", function () {
    $(".search-modal").css("display", "flex");
});

$(".close-btn").on("click", function () {
    $(".search-modal").css("display", "");
});


function reveal() {
    $(".reveal").each(function () {
        let elementTop = this.getBoundingClientRect().top;
        let windowHeight = window.innerHeight;
        let elementVisible = 100;

        if (elementTop < windowHeight - elementVisible) {
            $(this).addClass("active");
        } else {
            $(this).removeClass("active");
        }
    });
}

$(window).on("scroll", reveal);
reveal();
});





        </script>
        <div class="line"></div>
    <div class="newsletter">
        <p>Subscribe to our Newsletter</p>
        <div class="input-container">
            <input type="email" placeholder="Email" required>
            <button>Subscribe</button>
        </div>
    </div>
    <div class="line"> 
    </div>
    <div class="container-3">
        <div class="customer-care">Customer Care
            <nav>
                <a href="">CONTACT US</a>
                <a href="">CALL NOW: 0000000000</a>
                <a href="">FAQ</a>
                <a href="">TRACK YOUR ORDER</a>
                <a href="">BOOK AN APPOINTMENT</a>
                <a href="">ACCESSIBILITY</a>
                <a href="">SITTEMAP</a>
            </nav>
        </div>
        <div class="customer-care">Our Company
            <nav>
                <a href="">FIND A BOUTIQUE</a>
                <a href="">CAREERSCAREERS</a>
                <a href="">CARTIER AND CORPORATE SOCIAL <br> RESPONSIBILITY</a>
                <a href="">CREDITS</a>
            </nav>
        </div>
        <div class="customer-care">Legal Area
            <nav>
                <a href="">TERMS OF USE</a>
                <a href="">PRIVACY POLICY</a>
                <a href="">CONDITIONS OF SALE</a>
                <a href="">ACCESSIBILITY STATEMENT</a>
                <a href="">CALIFORNIA PRIVACY RIGHT</a>
                <a href="">HUMAN RIGHTS STATEMENT</a>
                <a href="">DO NOT SELL OR SHARE MY PERSONAL <br> INFORMATION</a>
            </nav>
        </div>
        <div class="follow-us" >Follow Us
            <nav>
                <a href=""><img src="img/instagram.png" alt=""></a>
                <a href=""><img src="img/facebook-app-symbol.png" alt=""></a>
                <a href=""><img src="img/twitter.png" alt=""></a>
                <a href=""><img src="img/youtube.png" alt=""></a>
            </nav>
        </div>
    </div>
    <div class="footer-1">
        <div class="footer-2">
            SHOP IN: VIETNAM

        </div>
        <div class="footer-2">COPYRIGHT © 2025 TACARA</div>
    </div>


    
</body>
</html>
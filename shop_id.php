<?php
session_start(); 

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



    <div class="line-natural"></div>zz
    <div class="container-title">
        <img src="img/titlenatural.png" alt="">
        <h1>ALL CREATIONS</h1>
    </div>
   
    <?php
// Khởi tạo biến breadcrumbs là một mảng trống
$breadcrumbs = [];

// Kết nối với cơ sở dữ liệu
include 'connect.php';

// Lấy category_id và subcategory_id từ URL
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$subcategory_id = isset($_GET['subcategory_id']) ? intval($_GET['subcategory_id']) : 0;

// Thêm "HOME" vào breadcrumb
$breadcrumbs[] = ['name' => 'HOME', 'url' => 'home.php'];

// Lấy tên danh mục chính (Category) nếu có category_id
if ($category_id > 0) {
    $result = $conn->query("SELECT name FROM categories WHERE id = $category_id");
    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
        $breadcrumbs[] = ['name' => $category['name'], 'url' => "shop_id.php?category_id=$category_id"];
    }
}

// Nếu có subcategory_id, thêm thông tin danh mục con
if ($subcategory_id > 0) {
    $result = $conn->query("SELECT name FROM subcategories WHERE id = $subcategory_id");
    if ($result->num_rows > 0) {
        $subcategory = $result->fetch_assoc();
        $breadcrumbs[] = ['name' => $subcategory['name'], 'url' => "shop_id.php?category_id=$category_id&subcategory_id=$subcategory_id"];
    }
}

// Đóng kết nối
$conn->close();
?>

<div class="breadcrumb">
    <?php if (count($breadcrumbs) > 0): ?>
        <?php foreach ($breadcrumbs as $index => $breadcrumb): ?>
            <!-- Không hiển thị dấu "/" trước phần đầu tiên (Home) -->
            <?php if ($index > 0): ?>
                <span>/</span>
            <?php endif; ?>
            
            <!-- Hiển thị liên kết cho danh mục cha và con -->
            <a href="<?= htmlspecialchars($breadcrumb['url']) ?>"><?= htmlspecialchars($breadcrumb['name']) ?></a>
        <?php endforeach; ?>
    <?php endif; ?>
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
        include 'connect.php';

        // Lấy subcategory_id từ URL, nếu không có thì mặc định là 0
        $subcategory_id = isset($_GET['subcategory_id']) ? intval($_GET['subcategory_id']) : 0;

       
        $sql = "SELECT id, image, title, title2, price FROM products";
        if ($subcategory_id > 0) {
            $sql .= " WHERE subcategory_id = $subcategory_id"; 
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="natural-products">
                    <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="Sản phẩm" class="product-image">
                    <div class="natural-title-product"><?= htmlspecialchars($row['title']) ?></div>
                    <div class="natural-title-product-2"><?= htmlspecialchars($row['title2']) ?></div>
                    <div class="price-product"><?= number_format($row['price'], 0, ',', '.') ?> VND</div>
                    <button class="select-button">SELECT</button>
                </div>
                <?php
            }
        } else {
            
            echo "<p>Không có sản phẩm nào trong danh mục này!</p>";
        }

      
        $conn->close();
    ?>
</div>



    </div>   
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
        $(document).ready(function () {
            var urlParams = new URLSearchParams(window.location.search);
            var subcategory_id = urlParams.get('subcategory_id');
            
            function loadProducts(filters, sort, subcategory_id) {
        console.log("Filters: ", filters);
        console.log("Sort: ", sort);
        console.log("Subcategory ID: ", subcategory_id);

        $.ajax({
            url: "fetch-products.php",
            type: "POST",
            data: { filters: filters, sort: sort, subcategory_id: subcategory_id },  
            success: function (response) {
                console.log("AJAX Response: ", response); 
                $(".products-container").html(response);  
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: ", error);
            }
        });
    }

   
    function getFiltersAndSort() {
        let filters = [];
        let sort = $("input[name='sort']:checked").val();  

        $(".filter-section input[type='checkbox']:checked").each(function () {
            filters.push($(this).val()); 
        });

        return { filters, sort };  
    }


    $("input[type='radio'], input[type='checkbox']").on("change", function () {
        let { filters, sort } = getFiltersAndSort();  
        loadProducts(filters, sort, subcategory_id); 
    });

    let { filters, sort } = getFiltersAndSort(); 
    loadProducts(filters, sort, subcategory_id);
            
            if (!subcategory_id) {
                function fetchProducts() {
                    let filters = [];

                
                    $(".filter-section input[type='checkbox']:checked").each(function () {
                        filters.push($(this).val()); 
                    });

                
                    $.ajax({
                        url: "fetch-products.php",
                        type: "POST",
                        data: { filters: filters },
                        success: function (response) {
                            $(".products-container").html(response);
                        }
                    });
                }

                
                $("input[type='checkbox']").on("change", function () {
                    fetchProducts();
                });

            
                fetchProducts();  
            }
        });
        function reveal() {
            let elements = document.querySelectorAll(".reveal");
            for (let i = 0; i < elements.length; i++) {
                let windowHeight = window.innerHeight;
                let elementTop = elements[i].getBoundingClientRect().top;
                let elementVisible = 100; 

                if (elementTop < windowHeight - elementVisible) {
                    elements[i].classList.add("active");
                } else {
                    elements[i].classList.remove("active");
                }
            }
        }

        window.addEventListener("scroll", reveal);

        
        document.addEventListener("DOMContentLoaded", reveal);
                document.querySelector(".search-icon").addEventListener("click", function () {
                document.querySelector(".search-modal").style.display = "flex";
            });

            document.querySelector(".close-btn").addEventListener("click", function () {
                document.querySelector(".search-modal").style.display = "";
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
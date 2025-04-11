<?php
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/test.css">
    <link rel="stylesheet" href="css/map.css">
  
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

    <div class="line-header"></div>
    
    <div class="breadcrumb-map">
      
        <div class="flex-link"> 
            <a href="home.php">HOME</a> | <a href="map.php">MAP</a>
        </div>
        
        <div class="info-map">
                <div class="left-side">
                    <h1>HÌNH ẢNH CỬA HÀNG</h1>
                    <img src="img/service3.png" alt="">
                    <h1>LIÊN HỆ VỚI CHÚNG TÔI</h1>
                    <form action="send_mail.php" method="POST" class="form-contact">
                        <input type="text" name="name" placeholder="Họ và tên" required>
                        <input type="text" name="phone" placeholder="Số điện thoại" required>
                        <div class="form-group">
                            <select class="choose-option" id="request" name="request" required>
                                <option value="">Chọn yêu cầu</option>
                                <option value="Tư vấn sản phẩm">Tư vấn sản phẩm</option>
                                <option value="Hỗ trợ bảo hành">Hỗ trợ bảo hành</option>
                                <option value="Chính sách thu mua/thu đổi">Chính sách thu mua/thu đổi</option>
                                <option value="Đối tác truyền thông">Đối tác truyền thông</option>
                                <option value="Đối tác đầu tư">Đối tác đầu tư</option>
                                <option value="Khác">Khác</option>
                            </select>
                        </div>
                        <div>
                            Khi cung cấp những thông tin trên, Tôi đồng ý với Điều khoản sử dụng và Chính sách bảo mật
                        </div>
                        <button class="btn-map" type="submit">LIÊN HỆ</button>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
                        <?php endif; ?>
                    </form>

                 
                </div>
                <div class="right-side">
                    <h1>Danh sách hệ thống cửa hàng</h1>
                    <div class="map-2">
                        <a href="https://www.google.com/maps/place/C%C3%A0+Ph%C3%AA+%C4%90i%E1%BB%83m+H%E1%BA%B9n%C2%B9/@10.8971895,106.6928823,17z/data=!3m1!4b1!4m6!3m5!1s0x3174d7c095964e2d:0x5e89b38bbd512173!8m2!3d10.8971842!4d106.6954572!16s%2Fg%2F1pp2tkhll?hl=vi-VN&entry=ttu&g_ep=EgoyMDI1MDQwMi4xIKXMDSoASAFQAw%3D%3D">Tacara Lái Thiêu, Bình Dương</a>
                        <p><strong>Địa chỉ:</strong> Lái Thiêu, Bình Dương</p>
                        <p><strong>Giờ làm việc:</strong> 8:30 đến 17:30</p>
                        <p><strong>Hotline:</strong> 00000000</p>
                    </div>
                </div>
        </div>
    </div>
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
                <a href=""><img src="img/tik-tok.png" alt=""></a>
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
    <script>
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
    document.addEventListener("DOMContentLoaded", function () {
        reveal();

        // Mở/đóng ô search
        document.querySelector(".search-icon").addEventListener("click", function () {
            document.querySelector(".search-modal").style.display = "flex";
        });

        document.querySelector(".close-btn").addEventListener("click", function () {
            document.querySelector(".search-modal").style.display = "";
        });

        // ⚠️ Kiểm tra đăng nhập khi gửi form LIÊN HỆ
        const isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;

        const form = document.querySelector(".form-contact");
        if (form) {
            form.addEventListener("submit", function (e) {
                if (!isLoggedIn) {
                    e.preventDefault();
                    alert("Vui lòng đăng nhập trước khi gửi liên hệ.");
                    window.location.href = "register.php"; // hoặc login.php nếu bạn tách login
                }
            });
        }
    });
</script>


    
</body>
</html>
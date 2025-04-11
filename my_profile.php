<?php
session_start();
include 'connect.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: register.php");
    exit();
}

// Xử lý cập nhật nếu có POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');

    if (!empty($phone)) {
        $update = $conn->prepare("UPDATE users SET phone = ? WHERE id = ?");
        $update->bind_param("si", $phone, $user_id);

        if ($update->execute()) {
            $_SESSION['message'] = "✅ Phone number updated successfully.";
        } else {
            $_SESSION['message'] = "❌ Failed to update phone number.";
        }
    } else {
        $_SESSION['message'] = "⚠️ Phone number cannot be empty.";
    }

    header("Location: profile.php");
    exit();
}

// Lấy thông tin người dùng
$query = "SELECT title, first_name, last_name, email, phone FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Lấy thông báo nếu có
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TACARA - Profile</title>
    <link rel="stylesheet" href="css/test.css">
    <link rel="stylesheet" href="css/profile.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
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


<div class="body-container">
    <div class="form-container">
        <div class="wellcome">
            <img src="img/bao.avif" alt="">
            <div class="text-content-profile">
                <span class="user-name">Welcome <?= htmlspecialchars($_SESSION['user_name'] ?? 'Guest'); ?></span>
                <div>Welcome to your account.</div>
                <div>You can manage your shopping experience at Tacara Online Store.</div>
            </div>
        </div>

        <div class="container-info-profile">
            <div class="flex-btn">
                <h2>My Cartier</h2>
                <div class="sidebar">
                    <a href="profile.php" class="active">Overview</a>
                    <a href="#profile">My Profile</a>
                    <a href="changepassword.php">My Password</a>
                    <a href="#orders">My Orders</a>
                    <a href="wishlist.php">My Wish List</a>
                    <a href="#addresses">My Addresses</a>
                    <a href="#services">My Services</a>
                    <a href="#collection">My Collection</a>
                    <a href="#subscriptions">My Subscriptions & Interests</a>
                </div>
                <a href="logout.php" class="btn-logout">Log out</a>
            </div>

            <div class="info-customer">
                <section id="profile">
                    <h2>My Profile</h2>
                    <form method="POST" action="my_profile.php">
                        <div class="mb-3">
                            <label>Full Name:</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($user['title'] . ' ' . $user['first_name'] . ' ' . $user['last_name']) ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label>Email:</label>
                            <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label>Phone Number:</label>
                            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="Enter your phone number">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>

                        <?php if (!empty($message)): ?>
                            <div class="alert alert-info mt-3"><?= htmlspecialchars($message) ?></div>
                        <?php endif; ?>
                    </form>
                </section>
            </div>
        </div>
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
        function toggleMobileMenu() {
            const nav = document.querySelector('.mobile-nav');
            nav.classList.toggle('open');
        }

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


</body>
</html>

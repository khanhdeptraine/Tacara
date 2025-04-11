<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    header('Content-Type: application/json');

    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
        exit;
    }

    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($new !== $confirm) {
        echo json_encode(['status' => 'error', 'message' => 'New passwords do not match']);
        exit;
    }

    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashed);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current, $hashed)) {
        echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect']);
        exit;
    }

    $new_hashed = password_hash($new, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $new_hashed, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Password updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update password']);
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/test.css">
    <link rel="stylesheet" href="css/profile.css">
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
                                <input type="text" placeholder="Search for products..." required>
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
                    echo '<a href="#" class="dropdown-toggle">' . htmlspecialchars($category['name']) . '</a>';
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
           
            <?php

                include 'connect.php';
                $user_id = $_SESSION['user_id'] ?? null;

                if (!$user_id) {
                    header("Location: login.php");
                    exit();
                }
                
                $query = "SELECT title, first_name, last_name, email FROM users WHERE id = $user_id";
                $result = $conn->query($query);
                
                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                } else {
                    $user = null;
                }

                $wishlist_query = "SELECT p.id, p.title, p.image, p.price FROM wishlist w
                JOIN products p ON w.product_id = p.id
                WHERE w.user_id = $user_id LIMIT 1"; 
                $wishlist_result = $conn->query($wishlist_query);

                $wishlist_items = [];
                if ($wishlist_result->num_rows > 0) {
                    while ($row = $wishlist_result->fetch_assoc()) {
                        $wishlist_items[] = $row;
                    }
                }

                $full_wishlist_query = "SELECT p.id, p.title, p.image, p.price FROM wishlist w
                        JOIN products p ON w.product_id = p.id
                        WHERE w.user_id = $user_id";
                $full_wishlist_result = $conn->query($full_wishlist_query);

                $full_wishlist_items = [];
                if ($full_wishlist_result->num_rows > 0) {
                    while ($row = $full_wishlist_result->fetch_assoc()) {
                        $full_wishlist_items[] = $row;
                    }
                }
                
            ?>
            <div class="container-info-profile">
                
                <div class="flex-btn">
                    <h2> My Cartier</h2>
                    <div class="sidebar">
                        <a href="profile.php">Overview</a>
                        <a href="#profile">My Profile</a>
                        <a href="changepassword.php"  class="active">My Password</a>
                        <a href="#orders">My Orders</a>
                        <a href="wishlist.php">My Wish List</a>
                        <a href="#addresses">My Addresses</a>
                        <a href="#services">My Services</a>
                        <a href="#collection">My Collection</a>
                        <a href="#subscriptions">My Subscriptions & Interests</a>
                    </div>
                    
                    <a href="logout.php" class="btn-logout">Log out</a>
                </div >
                <div class="right-content" id="password">
                    <div class="wellcome">
                          <img src="img/bao.avif" alt="">
                        <div class="text-content-profile">
                        <span class="user-name">Welcome <?php  echo htmlspecialchars($_SESSION['user_name'] ?? 'Guest'); ?></span>
                            <div>Welcome to your account.</div>
                            <div>You can manage your shopping experience at Tacara Online Store.</div>
                        </div>
                    </div>
                    
                    <h3>Change Password</h3>

                    <?php if (!empty($message)): ?>
                        <div class="alert alert-<?= $type ?>">
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php endif; ?>


                    <form method="POST" class="change-password-form">
                        <div class="mb-3">
                            <label for="current_password">Current Password</label>
                            <input type="password" name="current_password" id="current_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password">New Password</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" required>
                           
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-dark">Save</button>
                    </form>
                    
                    <div id="result"></div>
                </div>

               
            </div>
            
            <?php $conn->close(); ?>
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
        
        document.querySelector('.change-password-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('changepassword.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text); });
                }
                return response.json();
            })
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    this.reset();
                }
            })
            .catch(error => {
                alert('An error occurred. Please try again.');
                console.error('Fetch error:', error.message);
            });

            return false;
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


</body>
</html>

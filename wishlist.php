<?php
session_start(); 


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/test.css">
    <link rel="stylesheet" href="css/wishlist.css">
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


    <div class="body-container">
        <div class="form-container">
            <div class="wellcome">
                <img src="img/bao.avif" alt="">
                <div class="text-content-profile">
                  <span class="user-name">Welcome <?php  echo htmlspecialchars($_SESSION['user_name'] ?? 'Guest'); ?></span>
                    <div>Welcome to your account.</div>
                    <div>You can manage your shopping experience at Tacara Online Store.</div>
                </div>
            </div>
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
                WHERE w.user_id = $user_id";
                
                $wishlist_result = $conn->query($wishlist_query);
          
                $sizes = [];
                $size_query = $conn->query("SELECT product_id, size FROM product_sizes");
                while ($row = $size_query->fetch_assoc()) {
                    $product_id = $row['product_id'];
                    $size_value = $row['size'];
                    $sizes[$product_id][] = $size_value;
                }

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
                        
                        <a href="profile.php"  >Overview</a>
                        <a href="#profile">My Profile</a>
                        <a href="changepassword.php">My Password</a>
                        <a href="#orders">My Orders</a>
                        <a href="wishlist.php" class="active">My Wish List</a>
                        <a href="#addresses">My Addresses</a>
                        <a href="#services">My Services</a>
                        <a href="#collection">My Collection</a>
                        <a href="#subscriptions">My Subscriptions & Interests</a>
                    </div>
                    
                    <a href="logout.php" class="btn-logout">Log out</a>
                </div>
                <div class="info-customer">
                    
                   
                    <?php if ($wishlist_items): ?>
                        <div class="wishlist-grid">
                            <?php foreach ($wishlist_items as $item): ?>
                                <div class="wishlist-card">  
                                             <div class="background-img">
                                                <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="Product Image" class="wishlist-image">
                                            </div>
                                            <div class="title-wl"><?php echo htmlspecialchars($item['title']); ?></div>
                                            <div>  <?php echo number_format($item['price'], 0, ',', '.'); ?> VND</div>
                                        <select class="size-select" name="size">
                                            <option value="">Choose size</option>
                                            <?php foreach ($sizes[$item['id']] as $size): ?>
                                                <option value="<?= htmlspecialchars($size) ?>"><?= htmlspecialchars($size) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                            
                                            <button class="wishlist-btn" 
                                                    data-product-id="<?= $item['id'] ?>" 
                                                    data-size="<?= $item['size'] ?? 'default' ?>">
                                                Add to Cart
                                            </button>

                                            <button class="remove-btn">Remove</button>

                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="empty-wishlist">Your Wish List is empty.</p>
                    <?php endif; ?>
                </div>

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
       document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".wishlist-btn").forEach(button => {
    button.addEventListener("click", function () {
        const card = this.closest(".wishlist-card");
        const productId = this.dataset.productId;
        const size = card.querySelector(".size-select").value;

        if (!size) {
            alert("Please select a size before adding to cart.");
            return;
        }

        fetch("add_to_cart.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `product_id=${productId}&size=${encodeURIComponent(size)}`
        })
        .then(res => res.text())
        .then(response => {
            alert(response);
        });
    });
});


    document.querySelectorAll(".remove-btn").forEach(button => {
        button.addEventListener("click", function () {
            const productId = this.closest(".wishlist-card").querySelector(".wishlist-btn").dataset.productId;

            fetch("wishlist_action.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `product_id=${productId}&action=remove`
            })
            .then(res => res.text())
            .then(response => {
                alert(response);
                location.reload();
            });
        });
    });
});
           document.addEventListener("DOMContentLoaded", function() {
                const showAllWishlistButton = document.getElementById("show-all-wishlist");
                if (showAllWishlistButton) {
                    showAllWishlistButton.addEventListener("click", function() {
                        document.getElementById("full-wishlist").style.display = "block";
                        this.style.display = "none";
                    });
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


</body>
</html>

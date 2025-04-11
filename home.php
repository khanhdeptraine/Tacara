<?php
session_start(); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/test.css">
  
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


    <?php
        include 'connect.php'; 
        $sql = "SELECT image, title, description, button_link FROM designs ORDER BY id DESC LIMIT 1";
        $result = $conn->query($sql);
        $image = "img/default.png";
        $title = "Default Title";
        $description = "Default description.";
        $button_link = "#"; 
        if ($row = $result->fetch_assoc()) {
            echo '<div class="container-1 reveal">';
            echo '    <img src="' . htmlspecialchars($row['image']) . '" alt="Product Image">';
            echo '    <div class="overpay">';
            echo '        <h1>' . htmlspecialchars($row['title']) . '</h1>';
            echo '        <h2>' . htmlspecialchars($row['description']) . '</h2>';
            echo '        <button onclick="window.location.href=\'' . htmlspecialchars($row['button_link']) . '\'">SHOP THE COLLECTION</button>';
            echo '    </div>';
            echo '</div>';
        } else {  
            echo "Không có dữ liệu mới.";
        }
        $conn->close();
    ?>
    <div class="container-2">
    <?php
        include 'connect.php';

        $sql = "SELECT image, product_link FROM new_products ORDER BY id DESC LIMIT 2;";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="image-wrapper reveal">';
                echo '<img src="' . htmlspecialchars($row['image']) . '" alt="Product Image">';
                echo '<a href="' . htmlspecialchars($row['product_link']) . '" class="shop-btn">';
                echo '<span class="icon"><img src="img/shopping-bag.png" alt="Shopping bag"></span>';
                echo '<span class="text">SHOP THE LOOK</span>';
                echo '</a>';
                echo '</div>';
            }
        }

        $conn->close();
    ?>    
    </div>
    <?php
        include 'connect.php';
        $sql = "SELECT image, title, description, button_link FROM new_title_3 ORDER BY id DESC LIMIT 1";
        $result = $conn->query($sql);
        $image = "img/default.png";
        $title = "Default Title";
        $description = "Default description.";
        $button_link = "#"; 

        if ($row = $result->fetch_assoc()) {
            echo '
            <div class="container-1 reveal">
                <img src="' . htmlspecialchars($row['image']) . '" alt="Product Image">
                <div class="overpay">
                    <h1>' . htmlspecialchars($row['title']) . '</h1>
                    <h2>' . htmlspecialchars($row['description']) . '</h2>
                    <button onclick="window.location.href=\'' . htmlspecialchars($row['button_link']) . '\'">SHOP THE COLLECTION</button>
                </div>
            </div>';
        }
    ?>

    <?php
        include 'connect.php';
        $sql = "SELECT image, title, description, button_link FROM new_title_4 ORDER BY id DESC LIMIT 1";
        $result = $conn->query($sql);
        $image = "img/default.png";
        $title = "Default Title";
        $description = "Default description.";
        $button_link = "#"; 

        if ($row = $result->fetch_assoc()) {
            echo '
            <div class="container-1 my-4 reveal">
                <img src="' . htmlspecialchars($row['image']) . '" alt="Product Image">
                <div class="overpay">
                    <h1>' . htmlspecialchars($row['title']) . '</h1>
                    <h2>' . htmlspecialchars($row['description']) . '</h2>
                    <button onclick="window.location.href=\'' . htmlspecialchars($row['button_link']) . '\'">SHOP THE COLLECTION</button>
                </div>
            </div>';
        }
        
    ?>
    <?php
        include 'connect.php'; 
        $sql = "SELECT video, title, description, button_link FROM new_title_5 ORDER BY id DESC LIMIT 1";
        $result = $conn->query($sql);
        $video = "uploads/default.mp4"; 
        $title = "Default Title";
        $description = "Default description.";
        $button_link = "#"; 
        if ($row = $result->fetch_assoc()) {
            $video = $row['video'];
            $title = $row['title'];
            $description = $row['description'];
            $button_link = $row['button_link'];
        }
        $conn->close();
    ?>
    <div class="container-1 my-4 reveal">
        <video src="<?= htmlspecialchars($video) ?>" autoplay loop muted playsinline></video>
        <div class="overpay">
            <h1><?= htmlspecialchars($title) ?></h1>
            <h2><?= htmlspecialchars($description) ?></h2>
            <button onclick="window.location.href='<?= htmlspecialchars($button_link) ?>'">SHOP THE COLLECTION</button>
        </div>
    </div>

    
    </div>
    <div class="media">
        <div class="media-flex"><img src="img/box.png" alt="">Complimentary Delivery</div>
        <div class="media-flex"><img src="img/return.png" alt="">EASY RETURN OR EXCHANGE</div>
        <div class="media-flex"><img src="img/diamond-ring.png" alt="">Free Gift Wrapping</div>
    </div>
    <div class="line"></div>
    <div class="newsletter">
        <p>Subscribe to our Newsletter</p>
        <div class="input-container">
            <form action="subscribe.php" method="POST">
                <input type="email" name="email" placeholder="Email" required>
                <button type="submit">Subscribe</button>
            </form>
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

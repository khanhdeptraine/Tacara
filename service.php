<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/test.css">
    <link rel="stylesheet" href="css/service.css">
    <link href="https://fonts.googleapis.com/css2?family=Almarai&display=swap" rel="stylesheet">    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <div class="line-service"></div>
        <div class="container-service">
            <div class="img-service">
                <img src="img/service.png" alt="">
            </div>
            <div class="title1-service">
                    <h2>Service</h2>
                    <p>To honour the unique bond created by each Cartier creation, the Maison is dedicated to <br> accompanying its pieces over time, with services that ensure their beauty and longevity.</p>
            </div>
            <div class="service-container">
                <div class="service-item">
                    <img src="img/service1.png" alt="Service 1">
                    <div class="service-content">
                        <h3>THE RIGHT SERVICE FOR YOU</h3>
                        <p>Through your Tacara account you can request a service online or book an in-boutique appointment for personalized expert advice.</p>
                        <button>REQUEST A SERVICE</button>
                    </div>
                </div>
            
                <div class="service-item">
                    <img src="img/service2.png" alt="Service 2">
                    <div class="service-content">
                        <h3>YOUR SERVICE ORDER</h3>
                        <p>Through your Tacara account you can request a service online or book an in-boutique appointment for personalized expert advice.</p>
                        <button>REQUEST A SERVICE</button>
                    </div>
                </div>
            </div>
            <div class="service3"> 
                <div class="service3-image">
                    <img src="img/service3.png" alt="Jewelry Services">
                </div>
                <div class="service3-content">
                    <h2>JEWELRY SERVICES</h2>
                    <p>From personalization to adjustment and care, learn about the services that will ensure the beauty and longevity of your creations, including their cost estimates. </p>
                    <a href="">Learn More</a>
                 </div>
            </div>
            <div class="service3">        
                <div class="service3-content">
                    <h2>JEWELRY SERVICES</h2>
                    <p>From personalization to adjustment and care, learn about the services that will ensure the beauty and longevity of your creations, including their cost estimates. </p>
                    <a href="">Learn More</a>
                </div>
                <div class="service3-image">
                    <img src="img/service8.png" alt="Jewelry Services">
                </div>
            </div>
            <div class="explore">
                EXPLORE ALL SERVICES
            </div>
            <div class="service-container-4">
                <div class="service-item">
                    <img src="img/service4.png" alt="Service 1">
                    <div class="service-content">
                        <h3>THE RIGHT SERVICE FOR YOU</h3>
                        <p>Through your Tacara account you can request a service online or book an in-boutique appointment for personalized expert advice.</p>
                        <button>REQUEST A SERVICE</button>
                    </div>
                </div>
            
                <div class="service-item">
                    <img src="img/service5.png" alt="Service 2">
                    <div class="service-content">
                        <h3>YOUR SERVICE ORDER</h3>
                        <p>Through your Tacara account you can request a service online or book an in-boutique appointment for personalized expert advice.</p>
                        <button>REQUEST A SERVICE</button>
                    </div>
                </div>
            </div>
            <div class="service-container-5">
                <div class="service-item-5">
                    <img src="img/service6.png" alt="Service 1">
                    <div class="service-content-5">
                        <h3>THE RIGHT SERVICE FOR YOU</h3>
                        <p>Through your Tacara account you can request a service online or book an in-boutique appointment for personalized expert advice.</p>
                        <button>REQUEST A SERVICE</button>
                    </div>
                </div>
            
                <div class="service-item-5">
                    <img class="img7" src="img/service7.png" alt="Service 2">
                    <div class="service-content-5">
                        <h3>YOUR SERVICE ORDER</h3>
                        <p>Through your Tacara account you can request a service online or book an in-boutique appointment for personalized expert advice.</p>
                        <button>REQUEST A SERVICE</button>
                    </div>
                </div>
            </div>
            <div class="line-service"></div>
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/test.css">
    <link rel="stylesheet" href="css/contactus.css">
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
                <a href=""><img src="img/favorite.png" alt=""></a>
                <a href="register.php"><img src="img/user.png" alt=""></a>
                <a href=""><img src="img/placeholder.png" alt=""></a>
                <a href=""><img src="img/parcel.png" alt=""></a>
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
                
            
                $subcategories = $conn->query("SELECT name FROM subcategories WHERE category_id = " . $category['id']);

                if ($subcategories->num_rows > 0) {
                    echo '<div class="dropdown-menu"><div class="sub-dropdown">';
                    while ($sub = $subcategories->fetch_assoc()) {
                        echo '<a href="#">' . htmlspecialchars($sub['name']) . '</a>';
                    }
                    echo '</div></div>';
                }

                echo '</div>';
            }

            $conn->close();
            ?>
        </nav>

        
    </header>
    <div class="line-us"></div>
    <div class="contact-us">
        <div class="contact-img">
            <img src="img/contactus.png" alt="Contact Image">
        </div>
        <div class="container-us">
            <h1>Contact Us</h1>
            <h3>Our Tacara Ambassadors are delighted to assist you with <br> your orders, style advice, gift ideas, and more. Please select <br> your preferred method of contact below.</h3>
        </div>
    </div>
    <div class="line-us"></div>
    <div>
        <div class="contact-info">
            <div class="contact-item">
                <h2>Call Us</h2>
                <p>Cartier Client Relations hours (in EST)</p>
                <p>Weekdays - 9 a.m. to 9 p.m.</p>
                <p>Saturday - 10 a.m. to 9 p.m.</p>
                <p>Sunday - 10 a.m. to 8 p.m.</p>
                <p><strong>Tel.</strong> <a class="contact-link" href="tel:18002278437">1.800.227.8437</a></p>
            </div>
        
            <div class="contact-item">
                <h2>E-mail Us</h2>
                <p>A Cartier Ambassador will respond as soon as possible</p>
                <a href="mailto:contact@cartier.com" class="contact-link">Send an e-mail</a>
            </div>
        
            <div class="contact-item">
                <h2>Visit Us</h2>
                <p>Find your nearest Cartier boutique or authorized retailer</p>
                <a href="#" class="contact-link">Find a boutique</a>
            </div>
        
            <div class="contact-item">
                <h2>LIVE CHAT</h2>
                <p>Connect with a Cartier Ambassador when available</p>
                <a href="#" class="contact-link">Start Live Chat</a>
            </div>
        
            <div class="contact-item">
                <h2>Appointments</h2>
                <p>Join us for a personalized appointment at the boutique of your choice</p>
                <a href="#" class="contact-link">Request an appointment</a>
            </div>
        
            <div class="contact-item">
                <h2>FAQ</h2>
                <p>Find answers to commonly raised questions</p>
                <a href="#" class="contact-link">Explore FAQ</a>
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
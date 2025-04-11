<?php
session_start();
include 'connect.php';
require 'vendor/autoload.php';

// Generate a CSRF token if one doesn't exist or if logging out
if (empty($_SESSION['csrf_token']) || isset($_GET['logout'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token to prevent CSRF attacks
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token không hợp lệ.");
    }

    // Sanitize and validate form data
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $day = $_POST['day'];
    $month = $_POST['month'];
    $year = $_POST['year'];

   
    if (empty($email) || empty($password) || empty($first_name) || empty($last_name) || empty($day) || empty($month) || empty($year)) {
        echo "<script>alert('Vui lòng điền đầy đủ thông tin.'); window.history.back();</script>";
        exit;
    }

    $date_of_birth = "$year-$month-$day";

    
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

 
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email đã tồn tại, vui lòng sử dụng email khác!'); window.history.back();</script>";
    } else {
      
        $stmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name, date_of_birth) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $email, $hashed_password, $first_name, $last_name, $date_of_birth);

        if ($stmt->execute()) {
         
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'khanhngo78kc@gmail.com'; 
            $mail->Password = 'rvqd fmye xhlk lynp'; 
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('khanhngo78kc@gmail.com', 'Tacara Support');
            $mail->addReplyTo('support@tacara.com', 'Tacara');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Tacara – Xác nhận đăng ký tài khoản của bạn';
            $mail->Body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 0; margin: 0; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
                        h2 { text-align: center; font-size: 24px; margin-bottom: 20px; }
                        p { font-size: 16px; line-height: 1.6; margin-bottom: 10px; }
                        a { color: #0066cc; text-decoration: none; }
                        .footer { font-size: 14px; color: #888; text-align: center; margin-top: 30px; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <h2>Chào bạn,</h2>
                        <p>Cảm ơn bạn đã đăng ký tài khoản tại <strong>Tacara</strong> - nơi cung cấp những sản phẩm kim cương và trang sức cao cấp.</p>
                        <p>Để hoàn tất đăng ký, bạn chỉ cần xác nhận địa chỉ email bằng cách nhấn vào liên kết dưới đây:</p>
                        <p><a href='#'>Xác nhận địa chỉ email của bạn</a></p>
                        <p>Chúc bạn có những trải nghiệm tuyệt vời với chúng tôi!</p>
                        <p>Trân trọng,<br/><strong>Đội ngũ Tacara</strong></p>
                        <div class='footer'>
                            <p>Nếu bạn không đăng ký tài khoản, vui lòng bỏ qua email này.</p>
                            <p>Đọc thêm về <a href='privacy-policy.php'>Chính Sách Bảo Mật</a> của chúng tôi.</p>
                        </div>
                    </div>
                </body>
                </html>
            ";

            try {
                if ($mail->send()) {
                    echo "<script>alert('Tài khoản đã được tạo và email xác nhận đã được gửi!'); window.location.href='register.php';</script>";
                } else {
                    echo "<script>alert('Đăng ký thành công, nhưng không thể gửi email xác nhận.'); window.location.href='register.php';</script>";
                }
            } catch (Exception $e) {
                echo "<script>alert('Đăng ký thành công nhưng lỗi gửi email: " . $mail->ErrorInfo . "'); window.location.href='register.php';</script>";
            }
        } else {
            echo "<script>alert('Đăng ký thất bại, vui lòng thử lại!'); window.history.back();</script>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Almarai&display=swap" rel="stylesheet">   
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <title>Đăng Ký Tài Khoản</title>
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/test.css">
    
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
    <div class="line_register"></div>
    <div class="center-container">
        <div class="dieuhuong"> 
            <a href="">ALREADY REGISTERED</a>
            <a href="">CREATE YOUR ACCOUNT</a>
        </div>
   
        <div class="background-form">
            <form action="login.php" method="POST" class="login-form">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <h2>If you are already registered with Tacara, login here:</h2>

                <input type="email" id="email" name="email" placeholder="your@email.com" required>

                <div class="password-wrapper">
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                    <button type="button" id="togglePassword">Show</button>
                </div>

                <div class="options">
                    <a href="forgot-password.php">Forgot your password?</a>
                    <p>Read the <a href="privacy-policy.php">Privacy Policy</a> for further information.</p>
                </div>

                <button class="btn-login" type="submit">Login</button>
            </form>

         

            <div class="registration-form">
                <p>This space allows you to manage your personal information, e-Boutique orders, news updates, and newsletter subscriptions.</p>
                <form action="register.php" method="POST" class="form-register">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <div class="radio-group">
                        <label class="placeholder-title">Title*</label>
                        <div class="radio-options">
                            <input class="space-radio" type="radio" id="mr" name="title" value="Mr" required>
                            <label for="mr">Mr</label>
                            
                            <input class="space-radio" type="radio" id="mrs" name="title" value="Mrs" required>
                            <label for="mrs">Mrs</label>
                            
                            <input class="space-radio" type="radio" id="miss" name="title" value="Miss" required>
                            <label for="miss">Miss</label>
                        </div>
                    </div>               

                    <div class="name-group">
                        <input type="text" name="first_name" class="input-name" placeholder="First Name*" required>
                        <input type="text" name="last_name" class="input-name" placeholder="Last Name*" required>
                    </div>

                    <label class="placeholder-title">Date of birth*</label>
                    <div class="dob">
                        <select name="day" class="select-day" required>
                            <option value="">Day</option>
                            <?php for ($i = 1; $i <= 31; $i++) echo "<option>$i</option>"; ?>
                        </select>

                        <select name="month" class="select-month" required>
                            <option value="">Month</option>
                            <?php for ($i = 1; $i <= 12; $i++) echo "<option>$i</option>"; ?>
                        </select>

                        <select name="year" class="select-year" required>
                            <option value="">Year</option>
                            <?php for ($i = 1950; $i <= date('Y'); $i++) echo "<option>$i</option>"; ?>
                        </select>
                    </div>

                    <div class="email-password-group">
                        <input type="email" name="email" class="input-email" placeholder="Email Address*" required>
                        <input type="password" name="password" class="input-password" placeholder="Password" required>
                    </div>

                    <button type="submit" class="btn-submit">CREATE ACCOUNT</button>
                </form>
            </div>

            
            <script>
                document.getElementById("togglePassword").addEventListener("click", function() {
                let passwordInput = document.getElementById("password");
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    this.textContent = "Hide";
                } else {
                    passwordInput.type = "password";
                    this.textContent = "Show";
                }
            });
                document.addEventListener("DOMContentLoaded", function () {
                const loginTab = document.querySelector(".dieuhuong a:first-child");
                const registerTab = document.querySelector(".dieuhuong a:last-child");
                const loginForm = document.querySelector(".login-form");
                const registerForm = document.querySelector(".registration-form");

            
                registerForm.classList.add("hidden");

                loginTab.addEventListener("click", function (event) {
                    event.preventDefault();
                    loginForm.classList.remove("hidden");
                    loginForm.classList.add("visible");

                    registerForm.classList.remove("visible");
                    registerForm.classList.add("hidden");
                });

                registerTab.addEventListener("click", function (event) {
                    event.preventDefault();
                    registerForm.classList.remove("hidden");
                    registerForm.classList.add("visible");

                    loginForm.classList.remove("visible");
                    loginForm.classList.add("hidden");
                });
            });
            </script>
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



</body>
</html>

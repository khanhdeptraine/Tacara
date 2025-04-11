<?php
session_start();
$customer_phone = $_POST['phone'] ?? '';

require 'connect.php';
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    echo "<script>alert('Không có sản phẩm trong giỏ hàng hoặc chưa đăng nhập.'); window.location.href='cart.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Lấy thông tin người dùng
$stmt = $conn->prepare("SELECT title, first_name, last_name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$customer_name = $user['title'] . ' ' . $user['first_name'] . ' ' . $user['last_name'];
$customer_email = $user['email'];

// Tính tổng giá
$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// 👉 Lưu đơn hàng
$insert_order = $conn->prepare("INSERT INTO orders (user_id, customer_name, customer_phone, total_price) VALUES (?, ?, ?, ?)");
$insert_order->bind_param("issd", $user_id, $customer_name, $customer_phone, $total_price);

$insert_order->execute();
$order_id = $insert_order->insert_id;

// 👉 Lưu chi tiết đơn hàng
foreach ($_SESSION['cart'] as $item) {
    $insert_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, size, price) VALUES (?, ?, ?, ?, ?)");
    $insert_item->bind_param("iiisd", $order_id, $item['product_id'], $item['quantity'], $item['size'], $item['price']);
    $insert_item->execute();
}

// 👉 Gửi email thông báo
$mail = new PHPMailer(true);
try {
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'khanhngo78kc@gmail.com';
    $mail->Password = 'rvqd fmye xhlk lynp'; // Mật khẩu ứng dụng
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('khanhngo78kc@gmail.com', 'Tacara Website');
    $mail->addAddress('khanhngo78kc@gmail.com', 'Tacara Admin');
    $mail->addReplyTo($customer_email, $customer_name);

    $mail->isHTML(true);
    $mail->Subject = "🛒 Đơn hàng mới từ $customer_name";

    $body = "<h2>Thông tin đơn hàng mới</h2>";
    $body .= "<p><strong>Họ tên:</strong> $customer_name</p>";
    $body .= "<p><strong>Email:</strong> $customer_email</p>";
    $body .= "<p><strong>Số điện thoại:</strong> $customer_phone</p>";

    $body .= "<p><strong>Tổng tiền:</strong> " . number_format($total_price, 0, ',', '.') . " VND</p>";
    $body .= "<h4>Chi tiết sản phẩm:</h4><ul>";

    foreach ($_SESSION['cart'] as $item) {
        $body .= "<li><strong>{$item['title']}</strong> - Size: {$item['size']} - Số lượng: {$item['quantity']} - Giá: " . number_format($item['price'], 0, ',', '.') . " VND</li>";
    }
    $body .= "</ul>";

    $mail->Body = $body;
    $mail->send();

  
    unset($_SESSION['cart']);

    echo "<script>alert('Đơn hàng đã được gửi!'); window.location.href='home.php';</script>";
} catch (Exception $e) {
    echo "<script>alert('Không thể gửi email: {$mail->ErrorInfo}'); window.history.back();</script>";
}
?>

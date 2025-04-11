<?php
session_start(); 

// Tắt hiển thị lỗi PHP
ini_set('display_errors', 0);  // Tắt hiển thị lỗi
error_reporting(0);           // Không báo cáo lỗi

include 'connect.php';  

if (!isset($_SESSION['user_id'])) {
    echo "Bạn cần đăng nhập để thêm vào wishlist!";
    exit;
}

$user_id = $_SESSION['user_id'];  
$product_id = $_POST['product_id'] ?? '';  

if (empty($product_id)) {
    echo "Sản phẩm không hợp lệ.";
    exit;
}

$sql_check = "SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $user_id, $product_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    echo "Sản phẩm đã có trong wishlist của bạn!";
    exit;
}

$sql = "INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $product_id);

if ($stmt->execute()) {
    echo "Sản phẩm đã được thêm vào wishlist!";
} else {
    echo "Đã xảy ra lỗi khi thêm sản phẩm vào wishlist!";
}

$stmt->close();
$conn->close();
?>

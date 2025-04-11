<?php
session_start();
include 'connect.php';

$user_id = $_SESSION['user_id'] ?? null;
$product_id = $_POST['product_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$user_id || !$product_id || !$action) {
    echo 'Dữ liệu không hợp lệ.';
    exit;
}

$product_id = intval($product_id);

if ($action === 'remove') {
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    if ($stmt->execute()) {
        echo 'Đã xóa khỏi danh sách yêu thích.';
    } else {
        echo 'Lỗi khi xóa.';
    }
    $stmt->close();
} elseif ($action === 'add_to_cart') {
    // Giả sử bạn có bảng `cart(user_id, product_id)`
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $product_id);
    if ($stmt->execute()) {
        echo 'Đã thêm vào giỏ hàng.';
    } else {
        echo 'Sản phẩm đã có trong giỏ hàng.';
    }
    $stmt->close();
}

$conn->close();
?>

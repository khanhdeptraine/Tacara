<?php
session_start();
include 'connect.php';

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    echo "Bạn cần đăng nhập.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'] ?? '';
    $size = $_POST['size'] ?? '';
    $user_id = $_POST['user_id'] ?? $_SESSION['user_id'];

    if (empty($product_id) || empty($size)) {
        echo "Dữ liệu không hợp lệ.";
        exit;
    }

    // Truy vấn thông tin sản phẩm từ CSDL
    $stmt = $conn->prepare("SELECT id, title, title2, price, image, subcategory_id FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo "Sản phẩm không tồn tại.";
        exit;
    }

    // Kiểm tra giỏ hàng trong session, nếu chưa có thì tạo mới
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Kiểm tra xem sản phẩm đã có trong giỏ chưa
    $exists_in_cart = false;

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id && $item['size'] == $size) {
            $item['quantity'] += 1;
            $exists_in_cart = true;
            break;
        }
    }

    // Nếu sản phẩm chưa có trong giỏ, thêm mới
    if (!$exists_in_cart) {
        $_SESSION['cart'][] = [
            "product_id" => $product_id,
            "title" => $product['title'],
            "title2" => $product['title2'],
            "image" => $product['image'],
            "subcategory_id" => $product['subcategory_id'],
            "size" => $size,
            "price" => $product['price'],
            "quantity" => 1
        ];
    }

    echo "Sản phẩm đã được thêm vào giỏ hàng!";
}

$conn->close();
?>

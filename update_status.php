<?php
session_start();
require 'connect.php';

// Kiểm tra nếu không phải admin
// if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
//     echo "Bạn không có quyền truy cập!";
//     exit;
// }

// Xử lý form cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? '';

    // Danh sách trạng thái hợp lệ
    $valid_statuses = ['pending', 'processing', 'completed'];

    // Kiểm tra dữ liệu
    if ($order_id <= 0 || !in_array($status, $valid_statuses)) {
        die("Dữ liệu không hợp lệ.");
    }

    // Cập nhật trạng thái
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        header("Location: products_manager.php");
        exit;
    } else {
        echo "Cập nhật thất bại. Vui lòng thử lại.";
    }
} else {
    echo "Phương thức không hợp lệ.";
}
?>

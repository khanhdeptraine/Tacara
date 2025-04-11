<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'tacara';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$userCount = 0;
$productCount = 0;
$newProducts = [];

$userResult = $conn->query("SELECT COUNT(*) AS total FROM users");
if ($userResult && $row = $userResult->fetch_assoc()) {
    $userCount = $row['total'];
}

$productResult = $conn->query("SELECT COUNT(*) AS total FROM products");
if ($productResult && $row = $productResult->fetch_assoc()) {
    $productCount = $row['total'];
}

$newProductResult = $conn->query("SELECT title FROM products ORDER BY id DESC LIMIT 5");
if ($newProductResult) {
    while ($row = $newProductResult->fetch_assoc()) {
        $newProducts[] = $row['title'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị TACARA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f3f5;
        }
        .sidebar {
            height: 100vh;
            background-color: #212529;
        }
        .sidebar a {
            color: #dee2e6;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
        }
        .sidebar a:hover {
            background-color: #343a40;
            color: #fff;
        }
        .dashboard-card {
            border-radius: 16px;
            padding: 20px;
            color: white;
        }
        .dashboard-title {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar p-0">
            <h4 class="text-center text-white py-4 border-bottom">TACARA Admin</h4>
            <a href="#"><i class="bi bi-box"></i> Quản lý sản phẩm</a>
            <a href="#"><i class="bi bi-people"></i> Người dùng</a>
            <a href="#"><i class="bi bi-envelope"></i> Yêu cầu hỗ trợ</a>
            <a href="#"><i class="bi bi-gear"></i> Cài đặt</a>
            <a href="#"><i class="bi bi-graph-up"></i> Thống kê</a>
            <a href="#"><i class="bi bi-bag"></i> Đơn hàng Email</a>
            <a href="#"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a>
        </div>

        <!-- Content -->
        <div class="col-md-10 p-5">
            <h2 class="dashboard-title">Bảng điều khiển chính</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="dashboard-card bg-primary shadow-sm">
                        <h5><i class="bi bi-box-seam me-2"></i> Tổng sản phẩm</h5>
                        <p class="display-6"><?= $productCount ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card bg-success shadow-sm">
                        <h5><i class="bi bi-people-fill me-2"></i> Người dùng</h5>
                        <p class="display-6"><?= $userCount ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card bg-warning text-dark shadow-sm">
                        <h5><i class="bi bi-envelope-open-fill me-2"></i> Yêu cầu hỗ trợ</h5>
                        <p class="display-6">5</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card bg-danger shadow-sm">
                        <h5><i class="bi bi-cart-check-fill me-2"></i> Đơn hàng Email</h5>
                        <p class="display-6">8</p>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <h4>Hoạt động gần đây</h4>
                <ul class="list-group">
                    <?php foreach ($newProducts as $product): ?>
                        <li class="list-group-item">Đã thêm sản phẩm mới: <strong><?= htmlspecialchars($product) ?></strong></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
</body>
</html>

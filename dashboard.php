<?php
// Kết nối đến CSDL
$host = 'localhost';
$db = 'tacara';
$user = 'root';
$pass = ''; // nếu bạn có mật khẩu thì sửa lại
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn dữ liệu
$productCount = $conn->query("SELECT COUNT(*) AS total FROM designs")->fetch_assoc()['total'];
$supportCount = $conn->query("SELECT COUNT(*) AS total FROM contact")->fetch_assoc()['total'];
$emailOrderCount = $conn->query("SELECT COUNT(*) AS total FROM email_order")->fetch_assoc()['total'];

// Truy vấn hoạt động gần đây (giả sử từ bảng contact)
$recentActivities = $conn->query("SELECT name, email, message, created_at FROM contact ORDER BY created_at DESC LIMIT 3");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng điều khiển Quản trị - TACARA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: white;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 10px 20px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .card {
            border-radius: 12px;
        }
        .dashboard-title {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-0">
            <h4 class="text-center py-3">TACARA Admin</h4>
            <a href="#"><i class="bi bi-box"></i> Quản lý sản phẩm</a>
            <a href="#"><i class="bi bi-people"></i> Người dùng</a>
            <a href="#"><i class="bi bi-envelope"></i> Yêu cầu hỗ trợ</a>
            <a href="#"><i class="bi bi-gear"></i> Cài đặt</a>
            <a href="#"><i class="bi bi-graph-up"></i> Thống kê</a>
            <a href="#"><i class="bi bi-file-earmark"></i> Nội dung tiêu đề</a>
            <a href="#"><i class="bi bi-bag"></i> Đơn hàng qua email</a>
            <a href="#"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a>
        </div>
        <div class="col-md-10">
            <div class="p-4">
                <h2 class="dashboard-title">Bảng điều khiển chính</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card shadow p-3">
                            <h5>Tổng số sản phẩm</h5>
                            <p class="display-6"><?= $productCount ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow p-3">
                            <h5>Yêu cầu hỗ trợ</h5>
                            <p class="display-6"><?= $supportCount ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow p-3">
                            <h5>Đơn hàng qua Email</h5>
                            <p class="display-6"><?= $emailOrderCount ?></p>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <h4>Hoạt động gần đây</h4>
                    <ul class="list-group">
                        <?php while($row = $recentActivities->fetch_assoc()): ?>
                            <li class="list-group-item">
                                Người dùng <strong><?= htmlspecialchars($row['name']) ?></strong> (<?= htmlspecialchars($row['email']) ?>) đã gửi: 
                                "<?= htmlspecialchars($row['message']) ?>"
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

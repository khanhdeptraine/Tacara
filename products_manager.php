<?php
session_start();
require 'connect.php';

// Kiểm tra nếu không phải admin (nếu bạn đã có phân quyền thì check ở đây)
// if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
//     echo "Bạn không có quyền truy cập!";
//     exit;
// }

$orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 text-center">📦 Danh sách đơn hàng</h2>

    <?php if ($orders->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Tổng tiền</th>
                        <th>Ngày đặt</th>
                        <th>Chi tiết</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($order = $orders->fetch_assoc()): ?>
                    <?php
                        // Lấy email người dùng
                        $user_stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
                        $user_stmt->bind_param("i", $order['user_id']);
                        $user_stmt->execute();
                        $user_result = $user_stmt->get_result()->fetch_assoc();
                        $email = $user_result['email'] ?? 'N/A';

                        // Tô màu theo trạng thái
                        $status_class = match($order['status']) {
                            'pending' => 'table-warning',
                            'processing' => 'table-info',
                            'completed' => 'table-success',
                            default => ''
                        };
                    ?>
                    <tr class="<?= $status_class ?>">
                        <td><?= $order['id'] ?></td>
                        <td><?= htmlspecialchars($order['customer_name']) ?></td>
                        <td><?= htmlspecialchars($email) ?></td>
                        <td><?= htmlspecialchars($order['customer_phone']) ?></td>
                        <td><?= number_format($order['total_price'], 0, ',', '.') ?> VND</td>
                        <td><?= $order['created_at'] ?? '(Chưa có)' ?></td>
                        <td>
                            <button class="btn btn-sm btn-info" data-bs-toggle="collapse" data-bs-target="#details<?= $order['id'] ?>">Xem</button>
                        </td>
                        <td>
                            <form method="POST" action="update_status.php" class="d-flex justify-content-center align-items-center">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <select name="status" class="form-select form-select-sm w-auto me-2">
                                    <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Chưa xử lý</option>
                                    <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>Đang xử lý</option>
                                    <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Đã hoàn thành</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-success">✔</button>
                            </form>
                        </td>
                    </tr>
                    <tr class="collapse" id="details<?= $order['id'] ?>">
                        <td colspan="8">
                            <?php
                                $items_stmt = $conn->prepare("SELECT p.title, oi.quantity, oi.size, oi.price
                                                              FROM order_items oi 
                                                              JOIN products p ON oi.product_id = p.id
                                                              WHERE oi.order_id = ?");
                                $items_stmt->bind_param("i", $order['id']);
                                $items_stmt->execute();
                                $items = $items_stmt->get_result();
                            ?>
                            <ul class="list-group text-start">
                                <?php while ($item = $items->fetch_assoc()): ?>
                                    <li class="list-group-item">
                                        <strong><?= htmlspecialchars($item['title']) ?></strong>
                                        - Size: <?= $item['size'] ?>
                                        - Số lượng: <?= $item['quantity'] ?>
                                        - Giá: <?= number_format($item['price'], 0, ',', '.') ?> VND
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">Không có đơn hàng nào.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

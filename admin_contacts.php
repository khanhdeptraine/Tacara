<?php
require 'connect.php';

// Xử lý cập nhật trạng thái
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int) $_POST['id'];
    $stmt = $conn->prepare("UPDATE contact_requests SET status = 'Đã xử lý' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Lấy dữ liệu hiển thị
$results = $conn->query("SELECT cr.*, u.email FROM contact_requests cr 
                         LEFT JOIN users u ON cr.user_id = u.id
                         ORDER BY cr.created_at DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý liên hệ - Tacara</title>
    <link rel="stylesheet" href="css/admin.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">📬 Danh sách yêu cầu liên hệ từ khách hàng</h2>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên khách</th>
                <th>Số điện thoại</th>
                <th>Email</th>
                <th>Yêu cầu</th>
                <th>Ngày gửi</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($results->num_rows > 0): ?>
                <?php while ($row = $results->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['request_type']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                        <td><?= $row['status'] ?></td>
                        <td>
                            <?php if ($row['status'] === 'Chưa xử lý'): ?>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-success btn-sm">✓ Đánh dấu đã xử lý</button>
                                </form>
                            <?php else: ?>
                                <span class="text-success fw-bold">✓ Đã xử lý</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-center">Chưa có yêu cầu nào.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

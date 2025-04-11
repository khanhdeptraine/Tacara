<?php
include 'connect.php'; 

if (isset($_GET['subcategory_id'])) {
    $subcategory_id = $_GET['subcategory_id'];


    if (!is_numeric($subcategory_id)) {
        echo json_encode(['error' => 'subcategory_id không hợp lệ']);
        exit;
    }

    $sql = "SELECT size FROM size_types WHERE subcategory_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode(['error' => 'Lỗi trong câu truy vấn SQL']);
        exit;
    }

    $stmt->bind_param("i", $subcategory_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $sizes = [];
    while ($row = $result->fetch_assoc()) {
        $sizes[] = $row['size'];
    }

    $stmt->close();

    if (empty($sizes)) {
        echo json_encode(['message' => 'Không có size nào cho loại sản phẩm này']);
    } else {
        echo json_encode($sizes);
    }
} else {
    echo json_encode(['error' => 'subcategory_id không được gửi']);
}
?>

<?php include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subcategory_id = $_POST['subcategory_id'] ?? null;
    $title = $_POST['title'] ?? '';
    $title2 = $_POST['title2'] ?? '';
    $price = $_POST['price'] ?? 0;

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $image = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
      
            $sql = "INSERT INTO products (image, title, title2, price, subcategory_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssdi", $image, $title, $title2, $price, $subcategory_id);

            if ($stmt->execute()) {
                $product_id = $conn->insert_id; 

                
                $sql_size = "INSERT INTO product_sizes (product_id, size) 
                             SELECT ?, size FROM size_types WHERE subcategory_id = ?";
                $stmt_size = $conn->prepare($sql_size);
                $stmt_size->bind_param("ii", $product_id, $subcategory_id);
                $stmt_size->execute();
                $stmt_size->close();

                $message = "Thêm sản phẩm và size thành công!";
            } else {
                $message = "Lỗi: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Lỗi khi tải ảnh lên!";
        }
    } else {
        $message = "Vui lòng chọn ảnh!";
    }
}

$conn->close();
?>
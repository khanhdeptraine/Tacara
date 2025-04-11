<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_link = $_POST['product_link'];

    
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

  
    $stmt = $conn->prepare("INSERT INTO new_products (image, product_link) VALUES (?, ?)");
    $stmt->bind_param("ss", $target_file, $product_link);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "Thêm sản phẩm thành công!";
}
?>

<form action="add_title_2.php" method="POST" enctype="multipart/form-data">
    <label>Chọn ảnh:</label>
    <input type="file" name="image" required>
    <label>Nhập đường dẫn sản phẩm:</label>
    <input type="text" name="product_link" required>
    <button type="submit">Thêm</button>
</form>

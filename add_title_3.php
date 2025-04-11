<?php
    include 'connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $button_link = mysqli_real_escape_string($conn, $_POST['button_link']);

        // Handle the image upload
        $target_dir = "uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

        // Insert into the database
        $sql = "INSERT INTO new_title_3 (image, title, description, button_link) 
                VALUES ('$target_file', '$title', '$description', '$button_link')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Thêm thành công!";
        } else {
            echo "Lỗi: " . $conn->error;
        }
    }
    $conn->close();
?>

<form action="add_title_3.php" method="POST" enctype="multipart/form-data">
    <label>Chọn ảnh:</label>
    <input type="file" name="image" required>
    
    <label>Nhập tiêu đề (H1):</label>
    <input type="text" name="title" required>
    
    <label>Nhập mô tả (H2):</label>
    <textarea name="description" required></textarea>

    <label>Nhập đường dẫn nút button:</label>
    <input type="text" name="button_link" required>
    
    <button type="submit">Thêm</button>
</form>

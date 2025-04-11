<?php
    include 'connect.php'; 

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $button_link = $_POST['button_link'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imageName = $_FILES['image']['name'];
            $imageTemp = $_FILES['image']['tmp_name'];
            $imagePath = "uploads/" . basename($imageName);

            if (move_uploaded_file($imageTemp, $imagePath)) {
                $sql = "INSERT INTO designs (image, title, description, button_link) 
                        VALUES ('$imagePath', '$title', '$description', '$button_link')";
                if ($conn->query($sql) === TRUE) {
                    echo "Dữ liệu đã được thêm thành công!";
                } else {
                    echo "Lỗi: " . $conn->error;
                }
            } else {
                echo "Lỗi khi tải ảnh lên!";
            }
        }
    }

    $conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Thiết Kế Mới</title>
</head>
<body>
    <h2>Thêm Thiết Kế Mới</h2>

    <form action="add_title_1.php" method="POST" enctype="multipart/form-data">
        <div>
            <label for="title">Tiêu Đề:</label>
            <input type="text" name="title" id="title" required>
        </div>

        <div>
            <label for="description">Mô Tả:</label>
            <textarea name="description" id="description" rows="4" required></textarea>
        </div>

        <div>
            <label for="image">Chọn Ảnh:</label>
            <input type="file" name="image" id="image" required>
        </div>

        <div>
            <button type="submit">Thêm Thiết Kế</button>
        </div>
        <div>
    <label for="button_link">Đường Dẫn Button:</label>
    <input type="text" name="button_link" id="button_link" required>
</div>

    </form>
</body>
</html>

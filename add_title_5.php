<?php
    include 'connect.php';  // Kết nối cơ sở dữ liệu

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $button_link = $_POST['button_link'];

        // Xử lý video upload
        if (isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
            $videoName = $_FILES['video']['name'];
            $videoTemp = $_FILES['video']['tmp_name'];
            $videoPath = "uploads/" . basename($videoName);

            if (move_uploaded_file($videoTemp, $videoPath)) {
                // Insert dữ liệu vào bảng
                $sql = "INSERT INTO new_title_5 (video, title, description, button_link) 
                        VALUES ('$videoPath', '$title', '$description', '$button_link')";
                if ($conn->query($sql) === TRUE) {
                    echo "Dữ liệu đã được thêm thành công!";
                } else {
                    echo "Lỗi: " . $conn->error;
                }
            } else {
                echo "Lỗi khi tải video lên!";
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
    <title>Thêm Video Thiết Kế Mới</title>
</head>
<body>
    <h2>Thêm Video Thiết Kế Mới</h2>

    <form action="add_title_5.php" method="POST" enctype="multipart/form-data">
        <div>
            <label for="title">Tiêu Đề (H1):</label>
            <input type="text" name="title" id="title" required>
        </div>

        <div>
            <label for="description">Mô Tả (H2):</label>
            <textarea name="description" id="description" rows="4" required></textarea>
        </div>

        <div>
            <label for="video">Chọn Video:</label>
            <input type="file" name="video" id="video" required>
        </div>

        <div>
            <label for="button_link">Đường Dẫn Button:</label>
            <input type="text" name="button_link" id="button_link" required>
        </div>

        <div>
            <button type="submit">Thêm Video Thiết Kế</button>
        </div>
    </form>
</body>
</html>

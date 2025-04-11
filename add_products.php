<?php
include 'connect.php';

// Lấy danh mục
$categories = [];
$sql = "SELECT id, name FROM categories";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Lấy loại sản phẩm
$subcategories = [];
$sql = "SELECT id, name, category_id FROM subcategories";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subcategories[] = $row;
    }
}

$sizes = [];
// Nếu là request POST, xử lý thêm sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subcategory_id = $_POST['subcategory_id'];
    $title = $_POST['title'];
    $title2 = $_POST['title2'];
    $price = $_POST['price'];
    $selected_sizes = isset($_POST['sizes']) ? $_POST['sizes'] : [];

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $image = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO products (image, title, title2, price, subcategory_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            // Kiểm tra lỗi chuẩn bị câu lệnh SQL
            if ($stmt === false) {
                die('Lỗi chuẩn bị câu lệnh SQL: ' . $conn->error);
            }

            $stmt->bind_param("sssdi", $image, $title, $title2, $price, $subcategory_id);

            if ($stmt->execute()) {
                $product_id = $conn->insert_id;
                // Thêm các size vào bảng product_sizes
                foreach ($selected_sizes as $size) {
                    $sql_size = "INSERT INTO product_sizes (product_id, size) VALUES (?, ?)";
                    $stmt_size = $conn->prepare($sql_size);

                    if ($stmt_size === false) {
                        die('Lỗi chuẩn bị câu lệnh SQL size: ' . $conn->error);
                    }

                    $stmt_size->bind_param("is", $product_id, $size);
                    $stmt_size->execute();
                    $stmt_size->close();
                }
                echo "Thêm sản phẩm và size thành công!";
            } else {
                echo "Lỗi khi thêm sản phẩm: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Lỗi khi tải ảnh lên!";
        }
    } else {
        echo "Vui lòng chọn ảnh!";
    }
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <title>Thêm Sản Phẩm</title>
</head>
<body>

<h2>Thêm Sản Phẩm Mới</h2>

<form method="POST" enctype="multipart/form-data">
    <label>Chọn Danh Mục:</label>
    <select id="categorySelect" required>
        <option value="">-- Chọn danh mục --</option>
        <?php foreach ($categories as $category) { ?>
            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
        <?php } ?>
    </select>
    <br>

    <label>Chọn Loại Sản Phẩm:</label>
    <select name="subcategory_id" id="subcategorySelect" required>
        <option value="">-- Chọn loại sản phẩm --</option>
    </select>
    <br>

    <label>Chọn Size (dùng checkbox để chọn nhiều size):</label>
    <div id="sizeCheckboxes">
       
    </div>
    <br>

    <label>Chọn File:</label>
    <input type="file" name="image" required>
    <br>

    <label>Nhập Tên Sản Phẩm:</label>
    <input type="text" name="title" required>
    <br>

    <label>Nhập Mô Tả Ngắn:</label>
    <input type="text" name="title2" required>
    <br>

    <label>Nhập Giá Tiền (VND):</label>
    <input type="number" name="price" step="1000" required>
    <br>

    <button type="submit">Thêm Sản Phẩm</button>
</form>

<a href="products.php">Xem Danh Sách Sản Phẩm</a>

<script>
    var subcategories = <?= json_encode($subcategories) ?>;

    document.getElementById('categorySelect').addEventListener('change', function () {
        var categoryId = this.value;
        var subcategorySelect = document.getElementById('subcategorySelect');

        subcategorySelect.innerHTML = '<option value="">-- Chọn loại sản phẩm --</option>';

        subcategories.forEach(function (sub) {
            if (sub.category_id == categoryId) {
                var option = document.createElement('option');
                option.value = sub.id;
                option.textContent = sub.name;
                subcategorySelect.appendChild(option);
            }
        });
    });

    document.getElementById('subcategorySelect').addEventListener('change', function () {
        var subcategoryId = this.value;
        if (subcategoryId) {
            // Gửi yêu cầu lấy size theo loại sản phẩm
            fetch('get-sizes.php?subcategory_id=' + subcategoryId)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Mã lỗi: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data);  
                    var sizeCheckboxes = document.getElementById('sizeCheckboxes');
                    sizeCheckboxes.innerHTML = ''; 

                    if (data.length > 0) {
                        data.forEach(function(size) {
                            var label = document.createElement('label');
                            label.innerHTML = '<input type="checkbox" name="sizes[]" value="' + size + '">' + size;
                            sizeCheckboxes.appendChild(label);
                            sizeCheckboxes.appendChild(document.createElement('br'));
                        });
                    } else {
                        sizeCheckboxes.innerHTML = 'Không có size nào.';
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi lấy danh sách size:', error);
                });
        }
    });
</script>

</body>
</html>

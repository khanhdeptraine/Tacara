<?php
// Khởi tạo biến breadcrumbs là một mảng trống
$breadcrumbs = [];
?>

<?php
include 'connect.php';

// Lấy category_id và subcategory_id từ URL
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$subcategory_id = isset($_GET['subcategory_id']) ? intval($_GET['subcategory_id']) : 0;

// Lấy tên danh mục chính nếu có category_id
if ($category_id > 0) {
    $result = $conn->query("SELECT name FROM categories WHERE id = $category_id");
    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
        $breadcrumbs[] = ['name' => $category['name'], 'url' => "shop_id.php?category_id=$category_id"];
    }
}

// Lấy tên danh mục con nếu có subcategory_id
if ($subcategory_id > 0) {
    $result = $conn->query("SELECT name FROM subcategories WHERE id = $subcategory_id");
    if ($result->num_rows > 0) {
        $subcategory = $result->fetch_assoc();
        $breadcrumbs[] = ['name' => $subcategory['name'], 'url' => "shop_id.php?category_id=$category_id&subcategory_id=$subcategory_id"];
    }
}

$conn->close();
?>

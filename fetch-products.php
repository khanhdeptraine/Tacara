<?php
include 'connect.php';

$sort = $_POST['sort'] ?? '';
$filters = $_POST['filters'] ?? [];
$subcategory_id = $_POST['subcategory_id'] ?? '';
$category_id = $_POST['category_id'] ?? '';

// Câu lệnh SQL cơ bản
$sql = "SELECT p.id, p.image, p.title, p.title2, p.price FROM products p WHERE 1";

// Nếu lọc theo category_id
if (!empty($category_id)) {
    $category_id = intval($category_id);
    $sql .= " AND p.subcategory_id IN (
        SELECT id FROM subcategories WHERE category_id = $category_id
    )";
}

// Nếu lọc theo subcategory_id
if (!empty($subcategory_id)) {
    $subcategory_id = intval($subcategory_id);
    $sql .= " AND p.subcategory_id = $subcategory_id";
}

// Nếu có nhiều filters (danh sách subcategory)
if (!empty($filters)) {
    $subcategories = implode(',', array_map('intval', $filters));
    $sql .= " AND p.subcategory_id IN ($subcategories)";
}

// Sắp xếp
if ($sort == 'low-high') {
    $sql .= " ORDER BY p.price ASC";
} elseif ($sort == 'high-low') {
    $sql .= " ORDER BY p.price DESC";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="natural-products">
                <div class="product-wrapper">
                    <button class="wishlist-btn" data-product-id="' . $row['id'] . '">
                        <img src="img/heart.png" alt="Wishlist">
                    </button>
                    <img src="uploads/' . htmlspecialchars($row['image']) . '" alt="Sản phẩm" class="product-image">
                </div>
                <div class="natural-title-product">' . htmlspecialchars($row['title']) . '</div>
                <div class="natural-title-product-2">' . htmlspecialchars($row['title2']) . '</div>
                <div class="price-product">' . number_format($row['price'], 0, ',', '.') . ' VND</div>
                
                <button class="select-button">SELECT SIZE</button>

                <div class="size-selection" style="display: none;">
                    <select class="size-dropdown">
                        <option value="">Select Size</option>';

        // Truy vấn size theo sản phẩm
        $size_sql = "SELECT size FROM product_sizes WHERE product_id = " . $row['id'];
        $size_result = $conn->query($size_sql);

        if ($size_result->num_rows > 0) {
            while ($size_row = $size_result->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($size_row['size']) . '">' . htmlspecialchars($size_row['size']) . '</option>';
            }
        }

        echo '      </select>
                    <button class="add-to-bag" data-product-id="' . $row['id'] . '" style="display: none;">ADD TO BAG</button>
                </div>
            </div>';
    }
} else {
    echo "<p>Không có sản phẩm nào!</p>";
}

$conn->close();
?>
<script>
$(document).ready(function () {
    $(document).on("click", ".select-button", function () {
        var parent = $(this).closest(".natural-products");
        $(this).hide();
        parent.find(".size-selection").slideDown();
    });

    $(document).on("change", ".size-dropdown", function () {
        var parent = $(this).closest(".natural-products");
        var addToBagBtn = parent.find(".add-to-bag");

        if ($(this).val() !== "") {
            addToBagBtn.show();
        } else {
            addToBagBtn.hide();
        }
    });

    $(document).on("click", ".add-to-bag", function () {
        var product_id = $(this).data("product-id");
        var parent = $(this).closest(".natural-products");
        var size = parent.find(".size-dropdown").val();

        if (!size) {
            alert("Vui lòng chọn size trước khi thêm vào giỏ hàng!");
            return;
        }

        $.ajax({
            url: "add_to_cart.php",
            type: "POST",
            data: { product_id: product_id, size: size },
            success: function (response) {
                alert("Đã thêm vào giỏ hàng: " + response);
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
            }
        });
    });

    $(document).on("click", ".wishlist-btn", function () {
        var product_id = $(this).data("product-id");
        $.ajax({
            url: "add_wishlist.php",
            type: "POST",
            data: { product_id: product_id },
            success: function (response) {
                alert(response);
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
            }
        });
    });
});
</script>

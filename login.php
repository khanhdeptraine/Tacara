<?php
session_start();
include 'connect.php';

// Kiểm tra xem người dùng đã đăng nhập chưa, nếu đã đăng nhập thì chuyển hướng đến trang home
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Kiểm tra nếu email hoặc mật khẩu rỗng
    if (empty($email) || empty($password)) {
        echo "<script>alert('Vui lòng nhập email và mật khẩu!'); window.history.back();</script>";
        exit();
    }

    // Tìm kiếm người dùng trong cơ sở dữ liệu
    $stmt = $conn->prepare("SELECT id, first_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $first_name, $hashed_password);
        $stmt->fetch();
        
        // Kiểm tra mật khẩu
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $first_name;
          
            header("Location: home.php");
            exit();
        } else {
            echo "<script>alert('Mật khẩu không đúng!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Email không tồn tại!'); window.history.back();</script>";
    }
    
    $stmt->close();
    $conn->close();
}
?>

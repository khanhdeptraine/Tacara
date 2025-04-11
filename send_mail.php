<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $request = htmlspecialchars(trim($_POST['request']));
    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;

    if (empty($name) || empty($phone) || empty($request)) {
        echo "<script>alert('Vui lòng nhập đầy đủ thông tin!'); window.history.back();</script>";
        exit;
    }

    if ($user_id) {
        $stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $email = $user['email'];
        } else {
            echo "<script>alert('Không tìm thấy người dùng!'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('User ID không hợp lệ!'); window.history.back();</script>";
        exit;
    }

    // 👉 Lưu vào database
    $insert = $conn->prepare("INSERT INTO contact_requests (user_id, name, phone, request_type, created_at) VALUES (?, ?, ?, ?, NOW())");
    $insert->bind_param("isss", $user_id, $name, $phone, $request);
    if (!$insert->execute()) {
        echo "<script>alert('Lỗi khi lưu vào cơ sở dữ liệu!'); window.history.back();</script>";
        exit;
    }

    // 👉 Gửi email
    $subject = "Liên hệ từ khách hàng - $name";
    $body_html = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; background-color: #f9f9f9; color: #333; }
            .container { padding: 20px; background: #fff; border-radius: 8px; border: 1px solid #eee; }
            h2 { color: #d4af37; }
            p { line-height: 1.6; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Thông tin liên hệ từ website Tacara</h2>
            <p><strong>Họ tên:</strong> $name</p>
            <p><strong>Số điện thoại:</strong> $phone</p>
            <p><strong>Yêu cầu:</strong> $request</p>
            <p><strong>Email:</strong> $email</p>
        </div>
    </body>
    </html>
    ";

    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'khanhngo78kc@gmail.com';
        $mail->Password = 'rvqd fmye xhlk lynp'; // ⚠️ Đổi mật khẩu ứng dụng nếu cần
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('khanhngo78kc@gmail.com', 'Tacara Website');
        $mail->addAddress('khanhngo78kc@gmail.com', 'Tacara Admin');
        $mail->addReplyTo($email, $name);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body_html;

        $mail->send();
        echo "<script>alert('Thông tin đã được gửi thành công!'); window.location.href='home.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Gửi email thất bại: {$mail->ErrorInfo}'); window.history.back();</script>";
    }
}
?>

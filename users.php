<?php
include 'db.php';

$sql = "SELECT id, email, phone, password FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>عرض معلومات المستخدمين</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>عرض معلومات المستخدمين</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>البريد الإلكتروني</th>
            <th>رقم الهاتف</th>
            <th>كلمة السر</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            $key = 'your-secret-key';
            while($row = $result->fetch_assoc()) {
                // فصل الـ IV عن كلمة السر المشفرة
                $parts = explode(':', $row["password"]);
                $iv = base64_decode($parts[0]);
                $encrypted_password = $parts[1];

                // التحقق من طول الـ IV
                if (strlen($iv) !== 16) {
                    echo "<tr><td colspan='4'>خطأ في فك التشفير - IV غير صالح</td></tr>";
                    continue;
                }

                // فك التشفير
                $decrypted_password = openssl_decrypt($encrypted_password, 'aes-128-cbc', $key, 0, $iv);

                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["phone"] . "</td>";
                echo "<td>" . $decrypted_password . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>لا توجد بيانات</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>


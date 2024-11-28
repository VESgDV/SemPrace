<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../db/db_connect.php';

// Проверяем авторизацию
if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: /login');
    exit;
}

$sender_id = $_SESSION['uzivatel_id'];
$recipient_email = $_POST['recipient_email'];
$subject = $_POST['subject'];
$message = $_POST['message'];

// Найдем получателя по email
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$recipient_email]);
$recipient = $stmt->fetch(PDO::FETCH_ASSOC);

if ($recipient) {
    $recipient_id = $recipient['id'];
    $secret_key = 'v3ry$tr0ng$ecretK3y!'; // Замените на ваш секретный ключ

    // Функция для шифрования
    function encrypt($data, $key) {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv); // Сохраняем в формате "зашифрованные данные::вектор инициализации"
    }
        
    // Зашифровать сообщение перед сохранением
    $encrypted_message = encrypt($message, $secret_key);
    
    // Сохраняем сообщение в базе данных
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, recipient_id, subject, message) 
                           VALUES (?, ?, ?, ?)");
    $stmt->execute([$sender_id, $recipient_id, $subject, $encrypted_message]);

    header('Location: /zpravy');
} else {
    echo "Uživatel s tímto e-mailem nebyl nalezen.";
}
?>

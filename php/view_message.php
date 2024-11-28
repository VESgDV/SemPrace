<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../db/db_connect.php';


if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: /login');
    exit;
}

$message_id = $_GET['id'];
$user_id = $_SESSION['uzivatel_id'];


$stmt = $pdo->prepare("SELECT m.*, u.email AS sender_email FROM messages m 
                       JOIN users u ON m.sender_id = u.id 
                       WHERE m.id = ? AND (m.recipient_id = ? OR m.sender_id = ?)");
$stmt->execute([$message_id, $user_id, $user_id]);
$message = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$message) {
    echo "Zpráva nebyla nalezena nebo k ní nemáte přístup.";
    exit;
}

if ($message['recipient_id'] == $user_id) {
    $stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE id = ?");
    $stmt->execute([$message_id]);
}
$secret_key = 'v3ry$tr0ng$ecretK3y!'; 

$decrypted_message = decrypt($message['message'], $secret_key);
function decrypt($data, $key) {
    $decoded_data = base64_decode($data);
    $parts = explode('::', $decoded_data, 2);
    list($encrypted_data, $iv) = $parts;
    $decrypted = openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
    return $decrypted;
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zpráva</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include 'menu.php'; ?> 

    <div class="message-view-container">
        <h2>Zpráva</h2>
        <p><strong>Odesílatel:</strong> <?= htmlspecialchars($message['sender_email']) ?></p>
        <p><strong>Předmět:</strong> <?= htmlspecialchars($message['subject']) ?></p>
        <p><strong>Zpráva:</strong></p>
        <p><?= nl2br(htmlspecialchars($decrypted_message)) ?></p>

        <button type="button" class="secondary-btn" onclick="window.location.href='/zpravy'">Zpět na zprávy</button>
    </div>
</body>
</html>

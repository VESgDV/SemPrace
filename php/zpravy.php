<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../db/db_connect.php';


if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: /login');
    exit;
}

$user_id = $_SESSION['uzivatel_id'];


$stmt = $pdo->prepare("SELECT m.*, u.email AS sender_email FROM messages m 
                       JOIN users u ON m.sender_id = u.id 
                       WHERE m.recipient_id = ?");
$stmt->execute([$user_id]);
$received_messages = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $pdo->prepare("SELECT m.*, u.email AS recipient_email FROM messages m 
                       JOIN users u ON m.recipient_id = u.id 
                       WHERE m.sender_id = ?");
$stmt->execute([$user_id]);
$sent_messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zprávy</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include 'menu.php'; ?> 

    <div class="messages-container">
        <h2>Doručené zprávy</h2>
        <table>
            <tr>
                <th>Odesílatel</th>
                <th>Předmět</th>
                <th>Datum</th>
            </tr>
            <?php foreach ($received_messages as $msg): ?>
                <tr>
                    <td><?= htmlspecialchars($msg['sender_email']) ?></td>
                    <td><a href="/view_message?id=<?= $msg['id'] ?>"><?= htmlspecialchars($msg['subject']) ?></a></td>
                    <td><?= $msg['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Odeslané zprávy</h2>
        <table>
            <tr>
                <th>Příjemce</th>
                <th>Předmět</th>
                <th>Datum</th>
            </tr>
            <?php foreach ($sent_messages as $msg): ?>
                <tr>
                    <td><?= htmlspecialchars($msg['recipient_email']) ?></td>
                    <td><a href="/view_message?id=<?= $msg['id'] ?>"><?= htmlspecialchars($msg['subject']) ?></a></td>
                    <td><?= $msg['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Nová zpráva</h2>
        <form action="/send_message" method="post">
            <label for="recipient">Komu:</label>
            <input type="text" name="recipient_email" id="recipient" required>

            <label for="subject">Předmět:</label>
            <input type="text" name="subject" id="subject" required>

            <label for="message">Zpráva:</label>
            <textarea name="message" id="message" required></textarea>

            <button type="submit" class="primary-btn">Odeslat</button>
            <button type="button" class="secondary-btn" onclick="window.location.href='/zpravy'">Zrušit</button>
        </form>
    </div>

</body>
</html>

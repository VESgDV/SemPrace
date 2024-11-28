<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../db/db_connect.php';


if ($_SESSION['uzivatel_role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="admin-container">
        <h2>Správa uživatelů</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>E-mail</th>
                <th>Role</th>
                <th>Akce</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td><a href="/edit_user?id=<?= $user['id'] ?>">Upravit</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>

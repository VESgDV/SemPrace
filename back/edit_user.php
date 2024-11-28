<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../db/db_connect.php';


if ($_SESSION['uzivatel_role'] !== 'admin') {
    header('Location: /index');
    exit;
}


if (!isset($_GET['id'])) {
    header('Location: /admin_panel');
    exit;
}

$user_id = $_GET['id'];


$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$userWithoutPhoto = $user;
unset($userWithoutPhoto['photo']);

if (!$user) {
    die('Uživatel nenalezen.');
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jmeno = $_POST['jmeno'] ?? '';
    $prijmeni = $_POST['prijmeni'] ?? '';
    $telefon = $_POST['telefon'] ?? '';
    $email = $_POST['email'] ?? '';
    $role = $_POST['role'] ?? 'user'; 

    
    $stmt = $pdo->prepare("UPDATE users SET jmeno = ?, prijmeni = ?, telefon = ?, email = ?, role = ? WHERE id = ?");
    $stmt->execute([$jmeno, $prijmeni, $telefon, $email, $role, $user_id]);

    
    header('Location: /admin_panel');
    exit;
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editace uživatele</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="form-container">
        <h2>Editace uživatele</h2>

        <form method="post">
            <label for="jmeno">Jméno</label>
            <input type="text" id="jmeno" name="jmeno" value="<?= htmlspecialchars($userWithoutPhoto['jmeno']) ?>" required>

            <label for="prijmeni">Příjmení</label>
            <input type="text" id="prijmeni" name="prijmeni" value="<?= htmlspecialchars($userWithoutPhoto['prijmeni']) ?>" required>

            <label for="telefon">Telefonní číslo</label>
            <input type="tel" id="telefon" name="telefon" value="<?= htmlspecialchars($userWithoutPhoto['telefon']) ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($userWithoutPhoto['email']) ?>" required>

            <label for="role">Role</label>
            <select id="role" name="role">
                <option value="user" <?= $userWithoutPhoto['role'] === 'user' ? 'selected' : '' ?>>Uživatel</option>
                <option value="admin" <?= $userWithoutPhoto['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>

            <button type="submit">Uložit změny</button>
            <a href="/admin_panel" class="secondary-btn">Zpět</a>
            
        </form>
    </div>
</body>
</html>

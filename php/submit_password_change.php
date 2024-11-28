<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../db/db_connect.php';

if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: /login');
    exit;
}

$nove_heslo = password_hash($_POST['nove_heslo'], PASSWORD_DEFAULT);


$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
$stmt->execute([$nove_heslo, $_SESSION['uzivatel_id']]);


header('Location: /profil');
exit;
?>

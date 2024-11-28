<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../db/db_connect.php';

$email = $_POST['email'] ?? '';
$heslo = $_POST['heslo'] ?? '';


$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


if ($user && password_verify($heslo, $user['password'])) {
   
    $_SESSION['uzivatel_id'] = $user['id'];
    $_SESSION['uzivatel_role'] = $user['role'];
    header('Location: /index');
    exit;
} else {
   
    $_SESSION['login_error'] = 'Neplatný e-mail nebo heslo.';
    header('Location: /login');
    exit;
}
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../db/db_connect.php';

if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: /login');
    exit;
}


$jmeno = $_POST['jmeno'] ?? null;
$prijmeni = $_POST['prijmeni'] ?? null;
$telefon = $_POST['telefon'] ?? null;
$email = $_POST['email'] ?? null;


if (!$jmeno || !$prijmeni || !$telefon || !$email) {
    die('Všechna pole musí být vyplněna.');
}


if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $allowed = ['jpg', 'jpeg', 'png']; 
    $fileExtension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

    if (in_array($fileExtension, $allowed)) {
        
        $photo = file_get_contents($_FILES['foto']['tmp_name']);
        
       
        $stmt = $pdo->prepare("UPDATE users SET jmeno = ?, prijmeni = ?, telefon = ?, email = ?, photo = ? WHERE id = ?");
        $stmt->execute([$jmeno, $prijmeni, $telefon, $email, $photo, $_SESSION['uzivatel_id']]);
    } else {
        die('Formát obrázku není podporován.');
    }
} else {
    
    $stmt = $pdo->prepare("UPDATE users SET jmeno = ?, prijmeni = ?, telefon = ?, email = ? WHERE id = ?");
    $stmt->execute([$jmeno, $prijmeni, $telefon, $email, $_SESSION['uzivatel_id']]);
}


header('Location: /profil');
exit;

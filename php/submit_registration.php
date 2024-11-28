<?php
require '../db/db_connect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$email = $_POST['email'];
$telefon = $_POST['telefon'];
$jmeno = $_POST['jmeno'];
$prijmeni = $_POST['prijmeni'];
$login = $_POST['login'];
$heslo = password_hash($_POST['heslo'], PASSWORD_DEFAULT);


if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $allowed = ['jpg', 'jpeg', 'png']; 
    $fileExtension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);

    if (in_array(strtolower($fileExtension), $allowed)) {
        
        $photo = file_get_contents($_FILES['foto']['tmp_name']);
    } else {
        echo "Formát obrázku není podporován.";
        exit;
    }
} else {
    echo "Chyba při nahrávání obrázku.";
    exit;
}


$stmt = $pdo->prepare("INSERT INTO users (email, telefon, jmeno, prijmeni, login, password, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$email, $telefon, $jmeno, $prijmeni, $login, $heslo, $photo]);


header('Location: /login');
?>

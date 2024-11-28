<?php
session_start();
require '../db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $jmeno = $_POST['jmeno'];
    $prijmeni = $_POST['prijmeni'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];
    $pohlavi = $_POST['pohlavi'];
    $heslo = $_POST['heslo'];
    $hashedPassword = password_hash($heslo, PASSWORD_BCRYPT);
    $role = 'user';

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png']; 
        $fileExtension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    
        if (in_array(strtolower($fileExtension), $allowed)) {
            $photo = file_get_contents($_FILES['foto']['tmp_name']);
        } else {
            $_SESSION['error_message'] = "Formát obrázku není podporován.";
            header('Location: /registrace');
            exit;
        }
    } else {
        $_SESSION['error_message'] = "Chyba při nahrávání obrázku.";
        header('Location: /registrace');
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO users (login, jmeno, prijmeni, email, telefon, pohlavi, password, photo, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$login, $jmeno, $prijmeni, $email, $telefon, $pohlavi, $hashedPassword, $photo, $role]);

       
        header('Location: /login');
        exit;
    } catch (PDOException $e) {
       
        if ($e->getCode() == 23000) {  
            $_SESSION['error_message'] = "Login '$login' nebo email '$email' již existuje. Zvolte prosím jiný login nebo email.";
            header('Location: /registrace');  
            exit;
        } else {
            $_SESSION['error_message'] = "Chyba: " . $e->getMessage();
            header('Location: /registrace');
            exit;
        }
    }
}

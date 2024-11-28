<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../db/db_connect.php';

if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: /login');
    exit;
}

$education = $_POST['education'];
$experience = $_POST['experience'];
$languages = $_POST['languages'];
$skills = $_POST['skills'];
$interests = $_POST['interests'];


$resume_stmt = $pdo->prepare("SELECT * FROM resume WHERE user_id = ?");
$resume_stmt->execute([$_SESSION['uzivatel_id']]);

if ($resume_stmt->rowCount() > 0) {
    
    $stmt = $pdo->prepare("UPDATE resume SET education = ?, experience = ?, languages = ?, skills = ?, interests = ?, updated_at = NOW() WHERE user_id = ?");
    $stmt->execute([$education, $experience, $languages, $skills, $interests, $_SESSION['uzivatel_id']]);
} else {
    
    $stmt = $pdo->prepare("INSERT INTO resume (user_id, education, experience, languages, skills, interests, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute([$_SESSION['uzivatel_id'], $education, $experience, $languages, $skills, $interests]);
}

header('Location: /profil');
exit;
?>

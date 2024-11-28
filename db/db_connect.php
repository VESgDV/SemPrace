<?php
$host = 'localhost';
$dbname = 'webovkyDb';
$username = 'root'; 
$password = ''; 


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Chyba připojení k databázi: " . $e->getMessage());
}
?>

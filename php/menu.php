<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../db/db_connect.php';

if (isset($_SESSION['uzivatel_id'])) {
    $stmt = $pdo->prepare("SELECT login, photo FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['uzivatel_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<nav class="navbar">
    <ul>
        <li><a href="/index">Domů</a></li> 

        <?php if (isset($_SESSION['uzivatel_id'])): ?>
            <li><a href="/profil">Profil</a></li> 
            <li><a href="/resume">Resumé</a></li> 
            <li><a href="/zpravy">Zprávy</a></li> 

            <?php if ($_SESSION['uzivatel_role'] == 'admin'): ?>
                <li><a href="/admin_panel">Panel adminu</a></li> 
            <?php endif; ?>

            <li><a href="/logout">Odhlásit se</a></li> 

            <li class="user-info">
                <span><?= htmlspecialchars($user['login']) ?></span>
                <?php if ($user['photo']): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($user['photo']) ?>" alt="Profilová fotografie" class="user-photo" width="40" height="40">
                <?php else: ?>
                    <img src="/semprace/images/default-avatar.jpg" alt="Výchozí profilová fotografie" class="user-photo" width="40" height="40">
                <?php endif; ?>
            </li>

        <?php else: ?>
            <li><a href="/login">Přihlášení</a></li> 
            <li><a href="/registrace">Registrace</a></li> 
        <?php endif; ?>
    </ul>
</nav>

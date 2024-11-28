<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../db/db_connect.php';

if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: login.php');
    exit;
}


$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['uzivatel_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


$userWithoutPhoto = $user;
unset($userWithoutPhoto['photo']); 



?>


<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="form-container">
        <h2>Váš profil</h2>

        
        <div class="profile-photo">
            <?php if (!empty($user['photo'])): ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($user['photo']) ?>" alt="Profilová fotografie" width="150">
            <?php else: ?>
                <p>Profilová fotografie není nastavena.</p>
            <?php endif; ?>
        </div>

      
        <div id="profileDetails">
            <p><strong>Jméno:</strong> <?= htmlspecialchars($userWithoutPhoto['jmeno']) ?></p>
            <p><strong>Příjmení:</strong> <?= htmlspecialchars($userWithoutPhoto['prijmeni']) ?></p>
            <p><strong>Login:</strong> <?= htmlspecialchars($userWithoutPhoto['login']) ?></p>
            <p><strong>Telefonní číslo:</strong> <?= htmlspecialchars($userWithoutPhoto['telefon']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($userWithoutPhoto['email']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars($userWithoutPhoto['role']) ?></p>
        </div>

        
        <button type="button" onclick="toggleEditMode()">Upravit profil</button>

        
        <form id="editProfileForm" action="/update_profile" method="post" enctype="multipart/form-data" style="display:none;">
            <label for="jmeno">Jméno</label>
            <input type="text" id="jmeno" name="jmeno" value="<?= isset($userWithoutPhoto['jmeno']) ? htmlspecialchars($userWithoutPhoto['jmeno']) : '' ?>" required>
            
            <label for="prijmeni">Příjmení</label>
            <input type="text" id="prijmeni" name="prijmeni" value="<?= isset($userWithoutPhoto['prijmeni']) ? htmlspecialchars($userWithoutPhoto['prijmeni']) : '' ?>" required>
            
            <label for="login">Login</label>
            <input type="text" id="login" name="login" value="<?= isset($userWithoutPhoto['login']) ? htmlspecialchars($userWithoutPhoto['login']) : '' ?>" required>
            
            <label for="telefon">Telefonní číslo</label>
            <input type="tel" id="telefon" name="telefon" value="<?= isset($userWithoutPhoto['telefon']) ? htmlspecialchars($userWithoutPhoto['telefon']) : '' ?>" required>
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= isset($userWithoutPhoto['email']) ? htmlspecialchars($userWithoutPhoto['email']) : '' ?>" required>

            
            <label for="foto">Změnit profilovou fotografii</label>
            <input type="file" id="foto" name="foto" accept="image/*">

            
            <button type="submit" class="primary-btn">Uložit změny</button>
            <button type="button" class="secondary-btn" onclick="cancelEdit()">Zrušit změny</button>
        </form>

        
        <button type="button" onclick="window.location.href='/change_password'">Změnit heslo</button>
    </div>

    <script>
       
        function toggleEditMode() {
            document.getElementById('profileDetails').style.display = 'none';
            document.getElementById('editProfileForm').style.display = 'block';
        }

     
        function cancelEdit() {
            document.getElementById('editProfileForm').style.display = 'none';
            document.getElementById('profileDetails').style.display = 'block';
        }
    </script>
</body>
</html>

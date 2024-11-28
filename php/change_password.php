<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../db/db_connect.php';

if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: /login');
    exit;
}

?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Změna hesla</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script>
        function validatePasswordForm() {
            var newPassword = document.getElementById("nove_heslo").value;
            var confirmPassword = document.getElementById("potvrdit_heslo").value;

            if (newPassword.length < 6) {
                alert("Heslo musí mít alespoň 6 znaků.");
                return false;
            }

            if (newPassword !== confirmPassword) {
                alert("Hesla se neshodují.");
                return false;
            }

            return true;
        }

        function generatePassword() {
            var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            var passwordLength = 10;
            var password = "";

            for (var i = 0; i < passwordLength; i++) {
                var randomNumber = Math.floor(Math.random() * chars.length);
                password += chars.substring(randomNumber, randomNumber + 1);
            }

            document.getElementById("nove_heslo").value = password;
            document.getElementById("potvrdit_heslo").value = password;
        }

        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("nove_heslo");
            var confirmPasswordInput = document.getElementById("potvrdit_heslo");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                confirmPasswordInput.type = "text";
            } else {
                passwordInput.type = "password";
                confirmPasswordInput.type = "password";
            }
        }
    </script>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="form-container">
        <h2>Změna hesla</h2>
        <form action="/submit_password_change" method="post" onsubmit="return validatePasswordForm()">
            <label for="nove_heslo">Nové heslo</label>
            <input type="password" id="nove_heslo" name="nove_heslo" required> 
            <label for="potvrdit_heslo">Potvrdit heslo</label>
            <input type="password" id="potvrdit_heslo" name="potvrdit_heslo" required>
            <button type="button" onclick="generatePassword()">Generovat heslo</button>
            <button type="button" onclick="togglePasswordVisibility()">Zobrazit heslo</button>
            <button type="submit" class="primary-btn">Změnit heslo</button>
        </form>
       
        <button onclick="window.location.href='/profil'" class="secondary-btn">Zpět na profil</button>
    </div>
</body>
</html>

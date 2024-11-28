<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (isset($_SESSION['uzivatel_id'])) {
    header("Location: index.php"); 
    exit();
}


$error = $_SESSION['login_error'] ?? null;
unset($_SESSION['login_error']); 
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přihlášení</title>
    <link rel="stylesheet" href="../css/styles.css" >
    <link rel="stylesheet" href="../css/login.css" >
    <script>
        function validateLoginForm() {
            var email = document.getElementById("email").value;
            var heslo = document.getElementById("heslo").value;


            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                document.getElementById('error-message').innerHTML = "Prosím, zadejte platný e-mail.";
                return false;
            }


            if (heslo.length < 6) {
                document.getElementById('error-message').innerHTML = "Heslo musí mít alespoň 6 znaků.";
                return false;
            }

            
            return true;
        }
    </script>
</head>
<body>
    <?php include 'menu.php'; ?> 


    <div class="login-container">
        <div class="login_inner">
        <h2 class = "login_title">Přihlášení</h2>
        
        
        <?php if ($error): ?>
            <div id="error-message" style="color: red;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
       
        <div id="error-message" style="color: red;"></div>

       
        <form action="/login_process" method="post" onsubmit="return validateLoginForm()">
            
            <label class="login_label" for="email">E-MAIL</label>
            <input class="login_input"  type="email" id="email" name="email" required>
            
            <label class="login_label" for="heslo">Heslo</label>
            <input class="login_input" type="password" id="heslo" name="heslo" required>
            
            <button type="submit" class="login_buttonF" class="primary-btn">Přihlásit</button>
            <button type="button" class="login_buttonS" class="secondary-btn" onclick="window.location.href='/registrace'">Nová registrace</button>
            
        </form>
        <div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrace</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/registrace.css">

    
    <script>
        function validateRegistrationForm() {
            var email = document.getElementById("email").value;
            var telefon = document.getElementById("telefon").value;
            var heslo = document.getElementById("heslo").value;
            var potvrdit_heslo = document.getElementById("potvrdit_heslo").value;

            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert("Prosím, zadejte platný e-mail.");
                return false;
            }

            var telefonPattern = /^[0-9]{9}$/;
            if (!telefonPattern.test(telefon)) {
                alert("Prosím, zadejte platné telefonní číslo (např. 123456789).");
                return false;
            }

            var passwordPattern = /^[a-zA-Z0-9]+$/;
            if (!passwordPattern.test(heslo)) {
                alert("Heslo musí obsahovat pouze latinské znaky a číslice.");
                return false;
            }

            if (heslo.length < 6) {
                alert("Heslo musí mít alespoň 6 znaků.");
                return false;
            }

            if (heslo !== potvrdit_heslo) {
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

            document.getElementById("heslo").value = password;
            document.getElementById("potvrdit_heslo").value = password;
        }

        function togglePassword() {
            var passwordInput = document.getElementById("heslo");
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
        <div class="form_inner">
        <h2>Nová registrace</h2>
        <?php
        if (isset($_SESSION['error_message'])) {
            echo "<p class='error-message' style='color: red;'>" . $_SESSION['error_message'] . "</p>";
            unset($_SESSION['error_message']); 
        }
        ?>

        <form action="/submit_registration" method="post" enctype="multipart/form-data" onsubmit="return validateRegistrationForm()">
            <label for="login">Login</label>
            <input type="text" id="login" name="login" required>
    
            <label for="jmeno">Jméno</label>
            <input type="text" id="jmeno" name="jmeno" required>

            <label for="prijmeni">Příjmení</label>
            <input type="text" id="prijmeni" name="prijmeni" required>

            <label for="email">E-MAIL</label>
            <input type="email" id="email" name="email" required>

            <label for="telefon">Telefonní číslo</label>
            <div class="telefon_inner">
                <select class="country_code" id="country_code" name="country_code" required>
                    <option value="+420">+420 </option>
                    <option value="+1">+1 </option>
                    <option value="+44">+44 </option>
                    <option value="+49">+49 </option>
                </select>
                <input class="telefon_input" type="tel" id="telefon" name="telefon" placeholder="123456789">
            </div>

            <label for="pohlavi">Pohlaví</label>
            <select id="pohlavi" name="pohlavi" required>
               <option value="male">Muž</option>
               <option value="female">Žena</option>
            </select>

            <label for="foto">Profilová fotografie</label>
            <input type="file" id="foto" name="foto" accept="image/*" require>

            <div class="password-container">
                <label for="heslo">Heslo</label>
                <input type="password" id="heslo" name="heslo" required>
            </div>

            <label for="potvrdit_heslo">Potvrdit heslo</label>
            <input type="password" id="potvrdit_heslo" required>

            <button  type="button" class="generate-password-btn" onclick="generatePassword()">Generovat heslo</button>
            <button type="button" class="toggle-password-btn" onclick="togglePassword()">Zobrazit heslo</button>
            <button type="submit" class="primary-btn">Zaregistrovat</button>
        </form>
        <div>
    </div>
</body>
</html>

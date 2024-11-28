<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../db/db_connect.php';

if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: /login');
    exit;
}


$resume_stmt = $pdo->prepare("SELECT * FROM resume WHERE user_id = ?");
$resume_stmt->execute([$_SESSION['uzivatel_id']]);
$resume = $resume_stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upravit Resumé</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="form-container">
        <h2>Upravit resumé</h2>

        
        <form action="update_resume.php" method="post">
            <label for="education">Vzdělání</label>
            <textarea id="education" name="education" required><?= htmlspecialchars($resume['education']) ?></textarea>

            <label for="experience">Praxe</label>
            <textarea id="experience" name="experience" required><?= htmlspecialchars($resume['experience']) ?></textarea>

            <label for="languages">Jazyky</label>
            <textarea id="languages" name="languages" required><?= htmlspecialchars($resume['languages']) ?></textarea>

            <label for="skills">Technické znalosti</label>
            <textarea id="skills" name="skills" required><?= htmlspecialchars($resume['skills']) ?></textarea>

            <label for="interests">Zájmy</label>
            <textarea id="interests" name="interests" required><?= htmlspecialchars($resume['interests']) ?></textarea>

            <button type="submit" class="primary-btn">Uložit změny</button>
        </form>
    </div>
</body>
</html>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../db/db_connect.php';

if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['uzivatel_id'];

$stmt = $pdo->prepare("SELECT * FROM resume WHERE user_id = ?");
$stmt->execute([$user_id]);
$resume = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $education = $_POST['education'];
    $experience = $_POST['experience'];
    $languages = $_POST['languages'];
    $skills = $_POST['skills'];
    $interests = $_POST['interests'];

   
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['photo'];
        $imagePath = $image['tmp_name'];
        $imageType = mime_content_type($imagePath);

        $acceptedFormats = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp'];
        if (in_array($imageType, $acceptedFormats)) {
           
            switch ($imageType) {
                case 'image/png':
                    $img = imagecreatefrompng($imagePath);
                    break;
                case 'image/gif':
                    $img = imagecreatefromgif($imagePath);
                    break;
                case 'image/bmp':
                    $img = imagecreatefrombmp($imagePath);
                    break;
                default:
                    $img = imagecreatefromjpeg($imagePath);
            }

            
            $width = 800;
            $height = (int) (imagesy($img) * (800 / imagesx($img)));
            $resizedImg = imagecreatetruecolor($width, $height);
            imagecopyresampled($resizedImg, $img, 0, 0, 0, 0, $width, $height, imagesx($img), imagesy($img));

           
            ob_start();
            imagejpeg($resizedImg, null, 90);
            $imageData = ob_get_clean();

            imagedestroy($img);
            imagedestroy($resizedImg);

           
            if ($resume) {
                $stmt = $pdo->prepare("UPDATE resume SET education = ?, experience = ?, languages = ?, skills = ?, interests = ?, photo = ? WHERE user_id = ?");
                $stmt->execute([$education, $experience, $languages, $skills, $interests, $imageData, $user_id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO resume (user_id, education, experience, languages, skills, interests, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$user_id, $education, $experience, $languages, $skills, $interests, $imageData]);
            }
        } else {
            echo "Nepodporovaný formát obrázku.";
            exit;
        }
    } else {
      
        $stmt = $pdo->prepare("UPDATE resume SET education = ?, experience = ?, languages = ?, skills = ?, interests = ? WHERE user_id = ?");
        $stmt->execute([$education, $experience, $languages, $skills, $interests, $user_id]);
    }

    header('Location: /resume');
    exit;
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vytvořit resumé</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

    <?php include 'menu.php'; ?>

    <div class="form-container">
        <h2>Vytvořit / Upravit resumé</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="photo">Profilová fotka (JPEG, max šířka 800px):</label>
            <input type="file" id="photo" name="photo" accept="image/*">

            <?php if (!empty($resume['photo'])): ?>
                <div class="photo-preview">
                    <h4>Aktuální foto:</h4>
                    <img src="data:image/jpeg;base64,<?= base64_encode($resume['photo']) ?>" alt="Profilová fotka" width="150">
                </div>
            <?php endif; ?>
            <label for="education">Vzdělání:</label>
            <textarea id="education" name="education" required><?= htmlspecialchars($resume['education'] ?? '') ?></textarea>

            <label for="experience">Praxe:</label>
            <textarea id="experience" name="experience" required><?= htmlspecialchars($resume['experience'] ?? '') ?></textarea>

            <label for="languages">Jazyky:</label>
            <textarea id="languages" name="languages" required><?= htmlspecialchars($resume['languages'] ?? '') ?></textarea>

            <label for="skills">Technické znalosti:</label>
            <textarea id="skills" name="skills" required><?= htmlspecialchars($resume['skills'] ?? '') ?></textarea>

            <label for="interests">Zájmy:</label>
            <textarea id="interests" name="interests" required><?= htmlspecialchars($resume['interests'] ?? '') ?></textarea>

            

            <button type="submit" class="primary-btn">Uložit resumé</button>
            
        </form>
    </div>

</body>
</html>

<?php
session_start();
require_once __DIR__ . '/admin/includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $photo_path = null;

    $errors = [];
    if (empty($name)) $errors[] = 'Имя обязательно';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Некорректный email';

    if (empty($errors)) {
        if ($_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $photo_path = $upload_dir . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path);
        }

        $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone, photo_path) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $photo_path);

        if ($stmt->execute()) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Контакт успешно создан!'];
        } else {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Ошибка: ' . $stmt->error];
        }
        
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'message' => implode('<br>', $errors)];
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контактная книга</title>
    <link rel = "icon" href = "styles/phone-book.png">
    <link rel="stylesheet" href="styles/sas.css">
</head>
<body>
<div class="form-container">
        <h1>Добавить контакт</h1>
        
        <?php if (isset($_SESSION['alert'])): ?>
            <div class="alert alert-<?= $_SESSION['alert']['type'] ?>" id="system-alert">
                <span><?= $_SESSION['alert']['message'] ?></span>
                <button class="alert-close">&times;</button>
            </div>
            <?php unset($_SESSION['alert']); ?>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Имя*</label>
                <input type="text" id="name" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email*</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="phone">Телефон</label>
                <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="photo">Фотография</label>
                <div class="file-upload">
                    <label for="photo" class="file-upload-label">
                        <span class="file-upload-icon"></span>
                        <span class="file-upload-text">Выберите фотографию</span>
                        <span class="file-upload-hint">Перетащите файл или кликните для выбора</span>
                    </label>
                    <input type="file" id="photo" name="photo" accept="image/*">
                </div>
            </div>
            
            <button type="submit" class="btn">Сохранить</button>
        </form>
    </div>
    <script src = "js/index.js"> </script>
</body>
</html> 
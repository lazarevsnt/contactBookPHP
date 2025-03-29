<?php
session_start();
require_once __DIR__ . '/includes/db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['alert'] = [
            'type' => 'success', 
            'message' => 'Контакт успешно удалён!'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Ошибка при удалении: ' . $stmt->error
        ];
    }
    
    $stmt->close();
    $conn->close();
}

header("Location: index.php");
exit();
?>
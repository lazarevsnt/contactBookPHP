<?php
session_start();
require_once 'includes/db_connect.php'; 

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$total_query = "SELECT COUNT(*) as total FROM contacts";
$total_result = $conn->query($total_query);
$total_rows = $total_result->fetch_assoc()['total'];
$pages = ceil($total_rows / $limit);

$sql = "SELECT * FROM contacts ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

if (!$result) {
    die("Ошибка запроса: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href = "css/main.css">
    <title>Административная панель - Контакты</title>
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Контактная книга</h2>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="index.php" class="nav-link active">
                        <i>📋</i> Контакты
                    </a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">
                        <i>🚪</i> Выход
                    </a>
                </li>
            </ul>
        </aside>
        
        <main class="main-content">
            <div class="header">
                <h1>Список контактов</h1>
                <div class="user-menu">
                    <div class="user-avatar">A</div>
                    <a href="logout.php" class="logout-btn">Выйти</a>
                </div>
            </div>
            
            <div class="card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Имя</th>
                                <th>Email</th>
                                <th>Телефон</th>
                                <th>Фото</th>
                                <th>Дата</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= $row['name'] ?></td>
                                    <td><?= $row['email'] ?></td>
                                    <td><?= $row['phone'] ?></td>
                                    <td>
                                        <?php if ($row['photo_path']): ?>
                                            <img src="<?= $row['photo_path'] ?>" alt="Фото" class="user-photo">
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $row['created_at'] ?></td>
                                    <td>
                                        <form action="delete_contact.php" method="POST" onsubmit="return confirm('Вы уверены?');">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <button type="submit" class="btn btn-danger">Удалить</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

<?php if (isset($_SESSION['alert'])): ?>
    <div class="alert alert-<?= $_SESSION['alert']['type'] ?>" id="system-alert">
        <?= $_SESSION['alert']['message'] ?>
    </div>
    <?php unset($_SESSION['alert']); ?>
<?php endif; ?>

                <div class="pagination">
                    <?php for ($i = 1; $i <= $pages; $i++): ?>
                        <a href="?page=<?= $i ?>" class="page-link <?= $page == $i ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                </div>
            </div>
        </main>
    </div>
    <script src = "js/main.js"></script>
</body>
</html>
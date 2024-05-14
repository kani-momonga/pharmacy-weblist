<?php
$title = "管理ダッシュボード";
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

try {
    $db = new PDO('sqlite:' . __DIR__ . '/../pharmacy.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ユーザーと薬局を取得
    $usersStmt = $db->query("SELECT * FROM Users WHERE role = 'user'");
    $users = $usersStmt->fetchAll(PDO::FETCH_ASSOC);

    $pharmaciesStmt = $db->query("SELECT * FROM Pharmacies");
    $pharmacies = $pharmaciesStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("データベースエラー: " . $e->getMessage());
}
?>

<h1>管理ダッシュボード</h1>

<h2>ユーザー</h2>
<ul>
    <?php foreach ($users as $user): ?>
        <li>
            <?= htmlspecialchars($user['username']) ?> - 
            <?= $user['approved'] ? '承認済み' : '保留中' ?>
            <a href="admin_user_edit.php?id=<?= htmlspecialchars($user['id']) ?>">編集</a>
        </li>
    <?php endforeach; ?>
</ul>

<h2>薬局</h2>
<ul>
    <?php foreach ($pharmacies as $pharmacy): ?>
        <li>
            <?= htmlspecialchars($pharmacy['name']) ?>
            <a href="pharmacy_edit.php?id=<?= htmlspecialchars($pharmacy['id']) ?>">編集</a>
        </li>
    <?php endforeach; ?>
</ul>
</body>
</html>

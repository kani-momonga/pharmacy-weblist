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

<div class="container mt-4">
    <h1 class="mb-4">管理ダッシュボード</h1>
    
    <h2>ユーザー</h2>
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>ユーザー名</th>
                <th>ステータス</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= $user['approved'] ? '承認済み' : '保留中' ?></td>
                    <td><a href="admin_user_edit.php?id=<?= htmlspecialchars($user['id']) ?>" class="btn btn-primary btn-sm">編集</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2 class="mt-4">薬局</h2>
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>薬局名</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pharmacies as $pharmacy): ?>
                <tr>
                    <td><?= htmlspecialchars($pharmacy['name']) ?></td>
                    <td><a href="pharmacy_edit.php?id=<?= htmlspecialchars($pharmacy['id']) ?>" class="btn btn-primary btn-sm">編集</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2 class="mt-4">項目管理</h2>
    <p><a href="meta_keys.php" class="btn btn-secondary">項目管理ページへ</a></p>
</div>

</body>
</html>
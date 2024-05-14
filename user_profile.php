<?php
$title = "ユーザープロフィール";
include 'header.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$db = connectDb();

// ユーザー情報を取得
$stmt = $db->prepare("SELECT * FROM Users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// ユーザーが管理する薬局を取得
$stmt = $db->prepare("SELECT * FROM Pharmacies WHERE owner_id = ?");
$stmt->execute([$userId]);
$pharmacies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container mt-4">
    <h1 class="mb-4">ユーザープロフィール</h1>
    
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">ユーザー情報</h5>
            <p class="card-text"><strong>ユーザー名:</strong> <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></p>
            <p class="card-text"><strong>メール:</strong> <?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></p>
        </div>
    </div>

    <h2 class="mb-4">管理している薬局</h2>

    <?php if (count($pharmacies) > 0): ?>
        <?php foreach ($pharmacies as $pharmacy): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($pharmacy['name'], ENT_QUOTES, 'UTF-8') ?></h5>
                    <p class="card-text"><strong>住所:</strong> <?= htmlspecialchars($pharmacy['address'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="card-text"><strong>電話番号:</strong> <?= htmlspecialchars($pharmacy['phone'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="card-text"><strong>FAX番号:</strong> <?= htmlspecialchars($pharmacy['fax'], ENT_QUOTES, 'UTF-8') ?></p>
                    <a href="user_pharmacy_edit.php?id=<?= $pharmacy['id'] ?>" class="btn btn-primary">編集</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>管理している薬局がありません。</p>
    <?php endif; ?>

    <a href="pharmacy_register.php" class="btn btn-success">新規薬局を登録</a>
</div>

</body>
</html>

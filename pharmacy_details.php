<?php
$title = "薬局詳細";
include 'header.php';
require_once 'functions.php';

if (!isset($_GET['id'])) {
    die("薬局IDが必要です。");
}

$db = connectDb();
$pharmacyId = $_GET['id'];

// 薬局情報を取得
$stmt = $db->prepare("SELECT * FROM Pharmacies WHERE id = ?");
$stmt->execute([$pharmacyId]);
$pharmacy = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pharmacy) {
    die("薬局が見つかりません。");
}

// Meta情報を取得
$stmt = $db->prepare("SELECT * FROM PharmacyMeta WHERE pharmacy_id = ?");
$stmt->execute([$pharmacyId]);
$metaData = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h1 class="mb-4">薬局詳細</h1>

    <div class="card mb-4">
        <div class="card-body">
            <h2 class="card-title"><?= htmlspecialchars($pharmacy['name'], ENT_QUOTES, 'UTF-8') ?></h2>
            <p class="card-text"><strong>住所:</strong> <?= htmlspecialchars($pharmacy['address'], ENT_QUOTES, 'UTF-8') ?></p>
            <p class="card-text"><strong>電話番号:</strong> <?= htmlspecialchars($pharmacy['phone'], ENT_QUOTES, 'UTF-8') ?></p>
            <p class="card-text"><strong>FAX番号:</strong> <?= htmlspecialchars($pharmacy['fax'], ENT_QUOTES, 'UTF-8') ?></p>

            <?php if (!empty($metaData)): ?>
                <h5 class="card-subtitle mb-2 text-muted">追加情報</h5>
                <ul class="list-group list-group-flush">
                    <?php foreach ($metaData as $meta): ?>
                        <li class="list-group-item"><?= htmlspecialchars($meta['metakey'], ENT_QUOTES, 'UTF-8') ?>: <?= htmlspecialchars($meta['value'], ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <a href="index.php" class="btn btn-secondary">戻る</a>
</div>

</body>
</html>

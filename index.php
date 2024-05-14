<?php
$title = "薬局一覧";
include 'header.php';
require_once 'functions.php';

const PHARMACIES_PER_PAGE = 20;

$db = connectDb();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * PHARMACIES_PER_PAGE;

// 薬局情報と関連するMeta情報をJOINして取得
$stmt = $db->prepare("
    SELECT p.*, m.metakey, m.value
    FROM Pharmacies p
    LEFT JOIN PharmacyMeta m ON p.id = m.pharmacy_id
    WHERE p.approved = 1
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', PHARMACIES_PER_PAGE, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 薬局情報を整理
$pharmacies = [];
foreach ($results as $row) {
    $pharmacy_id = $row['id'];
    if (!isset($pharmacies[$pharmacy_id])) {
        $pharmacies[$pharmacy_id] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'address' => $row['address'],
            'phone' => $row['phone'],
            'fax' => $row['fax'],
            'meta' => []
        ];
    }
    if ($row['metakey']) {
        $pharmacies[$pharmacy_id]['meta'][] = [
            'metakey' => $row['metakey'],
            'value' => $row['value']
        ];
    }
}

// 総薬局数を取得
$totalStmt = $db->query("SELECT COUNT(*) FROM Pharmacies WHERE approved = 1");
$totalPharmacies = $totalStmt->fetchColumn();
$totalPages = ceil($totalPharmacies / PHARMACIES_PER_PAGE);
?>

<div class="container mt-4">
    <h1 class="mb-4">薬局一覧</h1>

    <?php foreach ($pharmacies as $pharmacy): ?>
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title"><?= htmlspecialchars($pharmacy['name'], ENT_QUOTES, 'UTF-8') ?></h2>
                <p class="card-text"><strong>住所:</strong> <?= htmlspecialchars($pharmacy['address'], ENT_QUOTES, 'UTF-8') ?></p>
                <p class="card-text"><strong>電話番号:</strong> <?= htmlspecialchars($pharmacy['phone'], ENT_QUOTES, 'UTF-8') ?></p>
                <p class="card-text"><strong>FAX番号:</strong> <?= htmlspecialchars($pharmacy['fax'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php if (!empty($pharmacy['meta'])): ?>
                    <h5 class="card-subtitle mb-2 text-muted">追加情報</h5>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($pharmacy['meta'] as $meta): ?>
                            <li class="list-group-item"><?= htmlspecialchars($meta['metakey'], ENT_QUOTES, 'UTF-8') ?>: <?= htmlspecialchars($meta['value'], ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <a href="pharmacy_details.php?id=<?= $pharmacy['id'] ?>" class="btn btn-primary mt-3">詳細を見る</a>
            </div>
        </div>
    <?php endforeach; ?>

    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="前へ">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item<?= $i == $page ? ' active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="次へ">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<style>
    .pagination .page-link {
        margin: 0 5px;
    }
</style>
</body>
</html>

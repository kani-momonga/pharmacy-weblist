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
    SELECT p.*, m.metakey, m.subject, m.value
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
            'email' => $row['email'],
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

<div class="container">
    <h1 class="mt-4 mb-4">薬局一覧</h1>

    <?php foreach ($pharmacies as $pharmacy): ?>
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title"><?php echo htmlspecialchars($pharmacy['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                <p class="card-text">住所: <?php echo htmlspecialchars($pharmacy['address'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="card-text">電話番号: <?php echo htmlspecialchars($pharmacy['phone'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="card-text">メール: <?php echo htmlspecialchars($pharmacy['email'], ENT_QUOTES, 'UTF-8'); ?></p>
                <?php if (!empty($pharmacy['meta'])): ?>
                    <h5 class="card-subtitle mb-2 text-muted">追加情報</h5>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($pharmacy['meta'] as $meta): ?>
                            <li class="list-group-item"><?php echo htmlspecialchars($meta['subject'], ENT_QUOTES, 'UTF-8'); ?>: <?php echo htmlspecialchars($meta['value'], ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <a href="pharmacy_detail.php?id=<?php echo $pharmacy['id']; ?>" class="btn btn-primary mt-3">詳細を見る</a>
            </div>
        </div>
    <?php endforeach; ?>

    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="前へ">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item<?php if ($i == $page) echo ' active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="次へ">
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

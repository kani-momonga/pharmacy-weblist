<?php
$title = "薬局編集";
include 'header.php';
require_once '../functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("薬局IDが必要です。");
}

$db = connectDb();
$pharmacyId = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $fax = $_POST['fax'];

    try {
        $stmt = $db->prepare("UPDATE Pharmacies SET name = ?, address = ?, phone = ?, fax = ? WHERE id = ?");
        $stmt->execute([$name, $address, $phone, $fax, $pharmacyId]);

        // Metaデータの更新
        foreach ($_POST['meta'] as $metakey => $value) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM PharmacyMeta WHERE pharmacy_id = ? AND metakey = ?");
            $stmt->execute([$pharmacyId, $metakey]);
            $exists = $stmt->fetchColumn();

            if ($exists) {
                $stmt = $db->prepare("UPDATE PharmacyMeta SET value = ? WHERE pharmacy_id = ? AND metakey = ?");
                $stmt->execute([$value, $pharmacyId, $metakey]);
            } else {
                $stmt = $db->prepare("INSERT INTO PharmacyMeta (pharmacy_id, metakey, value) VALUES (?, ?, ?)");
                $stmt->execute([$pharmacyId, $metakey, $value]);
            }
        }

        setFlashMessage("薬局情報が更新されました。");
        header("Location: pharmacy_edit.php?id=$pharmacyId");
        exit;
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
}

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
$metaDataAssoc = [];
foreach ($metaData as $meta) {
    $metaDataAssoc[$meta['metakey']] = $meta['value'];
}

// MetaKeysを取得
$metaKeys = getMetaKeys();
?>

<div class="container mt-4">
    <h1 class="mb-4">薬局編集</h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="name" class="form-label">薬局名</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($pharmacy['name'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">住所</label>
            <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($pharmacy['address'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">電話番号</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($pharmacy['phone'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="mb-3">
            <label for="fax" class="form-label">FAX</label>
            <input type="text" class="form-control" id="fax" name="fax" value="<?= htmlspecialchars($pharmacy['fax'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        
        <h2 class="mt-4">追加情報</h2>
        <?php foreach ($metaKeys as $metaKey): ?>
            <div class="mb-3">
                <label for="meta_<?= htmlspecialchars($metaKey['metakey'], ENT_QUOTES, 'UTF-8') ?>" class="form-label"><?= htmlspecialchars($metaKey['description'], ENT_QUOTES, 'UTF-8') ?></label>
                <input type="text" class="form-control" id="meta_<?= htmlspecialchars($metaKey['metakey'], ENT_QUOTES, 'UTF-8') ?>" name="meta[<?= htmlspecialchars($metaKey['metakey'], ENT_QUOTES, 'UTF-8') ?>]" value="<?= isset($metaDataAssoc[$metaKey['metakey']]) ? htmlspecialchars($metaDataAssoc[$metaKey['metakey']], ENT_QUOTES, 'UTF-8') : '' ?>">
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-primary">更新</button>
    </form>
</div>

</body>
</html>

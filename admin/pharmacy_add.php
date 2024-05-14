<?php
$title = "薬局追加";
include 'header.php';
require_once '../functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $fax = $_POST['fax'];
    $owner_id = $_SESSION['user_id'];

    $db = connectDb();

    try {
        // トランザクション開始
        $db->beginTransaction();

        // 薬局情報の追加
        $stmt = $db->prepare("INSERT INTO Pharmacies (name, address, phone, fax, owner_id, approved) VALUES (?, ?, ?, ?, ?, 1)");
        $stmt->execute([$name, $address, $phone, $fax, $owner_id]);
        $pharmacyId = $db->lastInsertId();

        // Metaデータの追加
        foreach ($_POST['meta'] as $metakey => $value) {
            if (!empty($value)) {
                $stmt = $db->prepare("INSERT INTO PharmacyMeta (pharmacy_id, metakey, value) VALUES (?, ?, ?)");
                $stmt->execute([$pharmacyId, $metakey, $value]);
            }
        }

        // トランザクション終了
        $db->commit();

        setFlashMessage("薬局が追加されました。");
        header("Location: pharmacy_add.php");
        exit;
    } catch (PDOException $e) {
        // トランザクションロールバック
        $db->rollBack();
        echo "エラー: " . $e->getMessage();
    }
}

// MetaKeysを取得
$metaKeys = getMetaKeys();
?>

<div class="container mt-4">
    <h1 class="mb-4">薬局追加</h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="name" class="form-label">薬局名</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">住所</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">電話番号</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">FAX</label>
            <input type="text" class="form-control" id="fax" name="fax" required>
        </div>
        
        <h2 class="mt-4">追加情報</h2>
        <?php foreach ($metaKeys as $metaKey): ?>
            <div class="mb-3">
                <label for="meta_<?= htmlspecialchars($metaKey['metakey'], ENT_QUOTES, 'UTF-8') ?>" class="form-label"><?= htmlspecialchars($metaKey['description'], ENT_QUOTES, 'UTF-8') ?></label>
                <input type="text" class="form-control" id="meta_<?= htmlspecialchars($metaKey['metakey'], ENT_QUOTES, 'UTF-8') ?>" name="meta[<?= htmlspecialchars($metaKey['metakey'], ENT_QUOTES, 'UTF-8') ?>]">
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-primary">追加</button>
    </form>
</div>

</body>
</html>

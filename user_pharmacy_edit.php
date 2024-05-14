<?php
$title = "薬局編集";
include 'header.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}

$userId = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    die("薬局IDが必要です。");
}

$db = connectDb();
$pharmacyId = $_GET['id'];

// 薬局情報を取得
$stmt = $db->prepare("SELECT * FROM Pharmacies WHERE id = ? AND owner_id = ?");
$stmt->execute([$pharmacyId, $userId]);
$pharmacy = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pharmacy) {
    die("薬局が見つかりません、またはアクセス権限がありません。");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $fax = $_POST['fax'];

    try {
        $stmt = $db->prepare("UPDATE Pharmacies SET name = ?, address = ?, phone = ?, fax = ? WHERE id = ? AND owner_id = ?");
        $stmt->execute([$name, $address, $phone, $fax, $pharmacyId, $userId]);

        setFlashMessage("薬局情報が更新されました。");
        header("Location: user_profile.php");
        exit;
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
}
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
            <label for="fax" class="form-label">FAX番号</label>
            <input type="text" class="form-control" id="fax" name="fax" value="<?= htmlspecialchars($pharmacy['fax'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">更新</button>
    </form>
</div>

</body>
</html>

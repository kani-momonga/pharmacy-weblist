<?php
require_once '../functions.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("薬局IDが必要です。");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_GET['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $result = editPharmacy($id, $name, $address, $phone, $email);
    if ($result === true) {
        echo "薬局情報が更新されました。";
    } else {
        echo $result;
    }
} else {
    $db = connectDb();
    $stmt = $db->prepare("SELECT * FROM Pharmacies WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $pharmacy = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pharmacy) {
        die("薬局が見つかりません。");
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>薬局編集</title>
</head>
<body>
    <h1>薬局編集</h1>
    <form method="POST" action="">
        <label for="name">薬局名:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($pharmacy['name']) ?>" required><br>
        <label for="address">住所:</label>
        <input type="text" name="address" value="<?= htmlspecialchars($pharmacy['address']) ?>" required><br>
        <label for="phone">電話番号:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($pharmacy['phone']) ?>" required><br>
        <label for="email">メールアドレス:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($pharmacy['email']) ?>" required><br>
        <button type="submit">更新</button>
    </form>
</body>
</html>
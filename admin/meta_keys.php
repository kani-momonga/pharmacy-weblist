<?php
$title = "メタキー管理";
include 'header.php';

require_once '../functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metakey = $_POST['metakey'];
    $description = $_POST['description'];

    $result = addMetaKey($metakey, $description);
    if ($result === true) {
        setFlashMessage("追加項目が追加されました。");
        header("Location: meta_keys.php");
    } else {
        echo $result;
    }
}

$metaKeys = getMetaKeys();
?>

<h1>登録項目名管理</h1>
<form method="POST" action="">
    <label for="metakey">項目名:</label>
    <input type="text" name="metakey" required><br>
    <label for="description">説明:</label>
    <input type="text" name="description" required><br>
    <button type="submit">追加</button>
</form>

<h2>既存の登録項目</h2>
<ul>
    <?php foreach ($metaKeys as $metaKey): ?>
        <li><?= htmlspecialchars($metaKey['metakey']) ?> - <?= htmlspecialchars($metaKey['description']) ?></li>
    <?php endforeach; ?>
</ul>
</body>
</html>

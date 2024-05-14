<?php
$title = "追加項目管理";
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
        exit;
    } else {
        echo $result;
    }
}

$metaKeys = getMetaKeys();
?>

<div class="container mt-4">
    <h1 class="mb-4">追加項目管理</h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="metakey" class="form-label">メタキー</label>
            <input type="text" class="form-control" id="metakey" name="metakey" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">説明</label>
            <input type="text" class="form-control" id="description" name="description" required>
        </div>
        <button type="submit" class="btn btn-primary">追加</button>
    </form>

    <h2 class="mt-4">既存の追加項目</h2>
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>追加項目</th>
                <th>説明</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($metaKeys as $metaKey): ?>
                <tr>
                    <td><?= htmlspecialchars($metaKey['metakey'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($metaKey['description'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>

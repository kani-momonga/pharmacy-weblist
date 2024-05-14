<?php
$title = "ユーザー編集";
include 'header.php';

require_once '../functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("ユーザーIDが必要です。");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_GET['id'];
    $approved = isset($_POST['approved']) ? 1 : 0;

    $result = approveUser($userId, $approved);
    if ($result === true) {
        setFlashMessage("ユーザーのステータスが更新されました。");
        header("Location: admin_user_edit.php?id=" . $userId);
        exit;
    } else {
        echo $result;
    }
} else {
    $db = connectDb();
    $stmt = $db->prepare("SELECT * FROM Users WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("ユーザーが見つかりません。");
    }
}
?>

<h1>ユーザー編集</h1>
<form method="POST" action="">
    <label for="approved">承認済み:</label>
    <input type="checkbox" name="approved" <?= $user['approved'] ? 'checked' : '' ?>><br>
    <button type="submit">更新</button>
</form>
</body>
</html>
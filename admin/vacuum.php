<?php
$title = "データベース最適化";
include 'header.php';
require_once '../functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

$db = connectDb();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db->exec("VACUUM");
        setFlashMessage("データベースの最適化が完了しました。");
        header("Location: vacuum.php");
        exit;
    } catch (PDOException $e) {
        $error_message = "エラー: " . $e->getMessage();
    }
}
?>

<div class="container mt-4">
    <h1 class="mb-4">データベース最適化</h1>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <button type="submit" class="btn btn-primary">最適化実行</button>
    </form>
</div>

</body>
</html>

<?php
session_start();
$message = isset($_SESSION['setup_message']) ? $_SESSION['setup_message'] : "メッセージはありません。";
$nextAction = isset($_SESSION['next_action']) ? $_SESSION['next_action'] : null;
unset($_SESSION['setup_message']);
unset($_SESSION['next_action']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>セットアップメッセージ</title>
</head>
<body>
    <h1>セットアップメッセージ</h1>
    <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php if ($nextAction): ?>
        <p><a href="<?php echo htmlspecialchars($nextAction, ENT_QUOTES, 'UTF-8'); ?>">次のステップへ進む</a></p>
    <?php endif; ?>
    <p><a href="../index.php">ホームへ移動</a></p>
</body>
</html>
<?php
$title = "ユーザー登録";
include 'header.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    try {
        // データベース接続
        $db = connectDb();

        // ユーザー名の重複チェック
        $stmt = $db->prepare("SELECT COUNT(*) FROM Users WHERE username = ?");
        $stmt->execute([$username]);
        $userCount = $stmt->fetchColumn();

        if ($userCount > 0) {
            $error_message = "このユーザー名は既に使用されています。";
        } else {
            $result = registerUser($username, $password, $email);
            if ($result === true) {
                setFlashMessage("ユーザー登録が完了しました。承認をお待ちください。");
                header("Location: user_register.php");
                exit;
            } else {
                $error_message = $result;
            }
        }
    } catch (PDOException $e) {
        $error_message = "エラー: " . $e->getMessage();
    }
}
?>

<div class="container mt-4">
    <h1 class="mb-4">ユーザー登録</h1>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="username" class="form-label">ユーザー名</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">パスワード</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">メールアドレス</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary">登録</button>
    </form>
</div>

</body>
</html>

<?php
$title = "管理者ログイン";
include 'header.php';
require_once '../functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = loginAdmin($username, $password);
    if ($result === true) {
        setFlashMessage("ログインしました。");
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error_message = $result;
    }
}

// 管理者ログイン機能
function loginAdmin($username, $password) {
    $db = connectDb();
    try {
        $stmt = $db->prepare("SELECT * FROM Users WHERE username = ? AND role = 'admin'");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            return true;
        } else {
            return "無効なユーザー名またはパスワードです。";
        }
    } catch (PDOException $e) {
        return "エラー: " . $e->getMessage();
    }
}
?>

<div class="container mt-4">
    <h1 class="mb-4">管理者ログイン</h1>
    
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
        <button type="submit" class="btn btn-primary">ログイン</button>
    </form>
</div>

</body>
</html>

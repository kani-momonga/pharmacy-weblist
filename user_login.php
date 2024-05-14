<?php
$title = "ユーザーログイン";
include 'header.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = loginUser($username, $password);
    if ($result === true) {
        header("Location: index.php");
        exit;
    } else {
        $error_message = $result;
    }
}
?>

<div class="container mt-4">
    <h1 class="mb-4">ユーザーログイン</h1>
    
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

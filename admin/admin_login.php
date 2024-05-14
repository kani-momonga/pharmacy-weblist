<?php
$title = "管理者ログイン";
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $db = new PDO('sqlite:' . __DIR__ . '/../pharmacy.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $db->prepare("SELECT * FROM Users WHERE username = ? AND role = 'admin'");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: admin_dashboard.php");
            exit;
        } else {
            setFlashMessage("ユーザー名またはパスワードが無効です。");
            header("Location: admin_login.php");
        }

    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
}
?>
<h1>管理者ログイン</h1>
<form method="POST" action="">
    <label for="username">ユーザー名:</label>
    <input type="text" name="username" required><br>
    <label for="password">パスワード:</label>
    <input type="password" name="password" required><br>
    <button type="submit">ログイン</button>
</form>
</body>
</html>

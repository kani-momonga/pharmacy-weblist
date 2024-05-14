<?php
session_start();

// パスワードハッシュ化関数
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// データベースファイルのパス
$dbFile = __DIR__ . '/../pharmacy.db';

// データベースファイルが存在するか確認
if (!file_exists($dbFile)) {
    $_SESSION['setup_message'] = "データベースが見つかりません。まずsetup.phpを実行してください。";
    unset($_SESSION['next_action']);
    header("Location: setup_message.php");
    exit;
}

// SQLiteデータベース接続の作成
try {
    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // フォーム送信の処理
        $adminUsername = $_POST['admin_username'];
        $adminPassword = $_POST['admin_password'];
        $adminEmail = $_POST['admin_email'];
        $smtpFromEmail = $_POST['smtp_from_email'];

        $hashedPassword = hashPassword($adminPassword);

        // 管理者ユーザーをデータベースに挿入
        $stmt = $db->prepare("INSERT INTO Users (username, password, email, role, approved) VALUES (?, ?, ?, 'admin', 1)");
        $stmt->execute([$adminUsername, $hashedPassword, $adminEmail]);

        // メール通知設定
        $emailEnabled = isset($_POST['email_enabled']) ? 1 : 0;
        $mailConfig = [
            'enabled' => $emailEnabled,
            'from_email' => $smtpFromEmail,
        ];

        if ($emailEnabled) {
            $mailConfig['smtp_host'] = $_POST['smtp_host'];
            $mailConfig['smtp_port'] = $_POST['smtp_port'];
            $mailConfig['smtp_user'] = $_POST['smtp_user'];
            $mailConfig['smtp_pass'] = $_POST['smtp_pass'];
        }

        file_put_contents(__DIR__ . '/../mail_config.json', json_encode($mailConfig, JSON_PRETTY_PRINT));

        $_SESSION['setup_complete'] = true;
        header("Location: setup_complete.php");
        exit;
    }

} catch (PDOException $e) {
    $_SESSION['setup_message'] = "初期設定に失敗しました: " . $e->getMessage();
    unset($_SESSION['next_action']);
    header("Location: setup_message.php");
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>初期設定</title>
</head>
<body>
    <h1>初期設定</h1>
    <form method="POST" action="">
        <h2>管理者ユーザー</h2>
        <label for="admin_username">ユーザー名:</label>
        <input type="text" name="admin_username" required><br>
        <label for="admin_password">パスワード:</label>
        <input type="password" name="admin_password" required><br>
        <label for="admin_email">メールアドレス:</label>
        <input type="email" name="admin_email" required><br>

        <h2>メール通知設定</h2>
        <label for="email_enabled">メール通知を有効にする:</label>
        <input type="checkbox" name="email_enabled" id="email_enabled" value="1"><br>
        <div id="email_settings" style="display: none;">
            <label for="smtp_from_email">送信元メールアドレス:</label>
            <input type="email" name="smtp_from_email"><br>
            <label for="smtp_host">SMTPホスト:</label>
            <input type="text" name="smtp_host"><br>
            <label for="smtp_port">SMTPポート:</label>
            <input type="text" name="smtp_port"><br>
            <label for="smtp_user">SMTPユーザー名:</label>
            <input type="text" name="smtp_user"><br>
            <label for="smtp_pass">SMTPパスワード:</label>
            <input type="password" name="smtp_pass"><br>
        </div>
        <button type="submit">送信</button>
    </form>

    <script>
        document.getElementById('email_enabled').addEventListener('change', function () {
            var emailSettings = document.getElementById('email_settings');
            if (this.checked) {
                emailSettings.style.display = 'block';
            } else {
                emailSettings.style.display = 'none';
            }
        });
    </script>
</body>
</html>
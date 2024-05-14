<?php
session_start();

function renderMenu() {
    if (isset($_SESSION['user_id'])) {
        if ($_SESSION['role'] === 'admin') {
            // 管理者用メニュー
            echo '<nav>
                <ul>
                    <li><a href="admin_dashboard.php">ダッシュボード</a></li>
                    <li><a href="pharmacy_add.php">薬局追加</a></li>
                    <li><a href="meta_keys.php">メタキー管理</a></li>
                    <li><a href="admin_user_edit.php">ユーザー編集</a></li>
                    <li><a href="../index.php">ホーム</a></li>
                    <li><a href="../logout.php">ログアウト</a></li>
                </ul>
            </nav>';
        } else {
            // 一般ユーザー用メニュー
            echo '<nav>
                <ul>
                    <li><a href="../pharmacy_list.php">薬局一覧</a></li>
                    <li><a href="../user_profile.php">プロフィール</a></li>
                    <li><a href="../index.php">ホーム</a></li>
                    <li><a href="../logout.php">ログアウト</a></li>
                </ul>
            </nav>';
        }
    } else {
        // ログインしていないユーザー用メニュー
        echo '<nav>
            <ul>
                <li><a href="../user_login.php">ログイン</a></li>
                <li><a href="../user_register.php">新規登録</a></li>
                <li><a href="../index.php">ホーム</a></li>
            </ul>
        </nav>';
    }
}

function renderFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        echo '<div class="flash-message">' . htmlspecialchars($_SESSION['flash_message'], ENT_QUOTES, 'UTF-8') . '</div>';
        unset($_SESSION['flash_message']);
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <style>
        nav ul {
            list-style-type: none;
            padding: 0;
        }
        nav ul li {
            display: inline;
            margin-right: 10px;
        }
        .flash-message {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <?php renderMenu(); ?>
    <?php renderFlashMessage(); ?>

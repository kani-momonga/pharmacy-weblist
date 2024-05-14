<?php
session_start();

function renderMenu() {
    if (isset($_SESSION['user_id'])) {
        if ($_SESSION['role'] === 'admin') {
            // 管理者用メニュー
            echo '<nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">管理ダッシュボード</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">ダッシュボード</a></li>
                            <li class="nav-item"><a class="nav-link" href="pharmacy_add.php">薬局追加</a></li>
                            <li class="nav-item"><a class="nav-link" href="meta_keys.php">追加項目管理</a></li>
                            <li class="nav-item"><a class="nav-link" href="admin_user_edit.php">ユーザー編集</a></li>
                            <li class="nav-item"><a class="nav-link" href="../index.php">ホーム</a></li>
                            <li class="nav-item"><a class="nav-link" href="../logout.php">ログアウト</a></li>
                        </ul>
                    </div>
                </div>
            </nav>';
        } else {
            // 一般ユーザー用メニュー
            echo '<nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">薬局管理システム</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="../pharmacy_list.php">薬局一覧</a></li>
                            <li class="nav-item"><a class="nav-link" href="../user_profile.php">プロフィール</a></li>
                            <li class="nav-item"><a class="nav-link" href="../index.php">ホーム</a></li>
                            <li class="nav-item"><a class="nav-link" href="../logout.php">ログアウト</a></li>
                        </ul>
                    </div>
                </div>
            </nav>';
        }
    } else {
        // ログインしていないユーザー用メニュー
        echo '<nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">薬局管理システム</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="../user_login.php">ログイン</a></li>
                        <li class="nav-item"><a class="nav-link" href="../user_register.php">新規登録</a></li>
                        <li class="nav-item"><a class="nav-link" href="../index.php">ホーム</a></li>
                    </ul>
                </div>
            </div>
        </nav>';
    }
}

function renderFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        echo '<div class="alert alert-info" role="alert">' . htmlspecialchars($_SESSION['flash_message'], ENT_QUOTES, 'UTF-8') . '</div>';
        unset($_SESSION['flash_message']);
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <style>
        .pagination a {
            margin: 0 5px;
            text-decoration: none;
            color: #007BFF;
        }
        .pagination a.active {
            font-weight: bold;
            text-decoration: underline;
        }
        .navbar-nav .nav-link {
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <?php renderMenu(); ?>
    <div class="container">
        <?php renderFlashMessage(); ?>

<?php
session_start();

// データベースファイルのパス
$dbFile = __DIR__ . '/../pharmacy.db';

// データベースファイルが既に存在するか確認
if (file_exists($dbFile)) {
    $_SESSION['setup_message'] = "データベースは既に存在します。セットアップは不要です。";
    header("Location: setup_message.php");
    exit;
}

$setupSQLFile = __DIR__ . '/setup.sql';
// データベースセットアップSQLファイルが存在するか確認
if (!file_exists($setupSQLFile)) {
    $_SESSION['setup_message'] = "データベースセットアップに必要なSQLファイルが存在しません。ファイルの配置をやり直してください。";
    header("Location: setup_message.php");
    exit;
}

// 新しいSQLiteデータベースを作成
try {
    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // setup.sqlファイルを読み込む
    $setupSql = file_get_contents($setupSQLFile);

    // テーブル作成のSQLコマンドを実行
    $db->exec($setupSql);

    $_SESSION['setup_message'] = "データベースのセットアップが完了しました。";
    $_SESSION['next_action'] = "initial_setup.php";
    header("Location: setup_message.php");
} catch (PDOException $e) {
    $_SESSION['setup_message'] = "データベースのセットアップに失敗しました: " . $e->getMessage();
    unset($_SESSION['next_action']);
    header("Location: setup_message.php");
}
?>

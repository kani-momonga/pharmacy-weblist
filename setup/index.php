<?php
session_start();

// データベースファイルのパス
$dbFile = __DIR__ . '/../pharmacy.db';
// メール設定ファイルのパス
$mailConfigFile = __DIR__ . '/../mail_config.json';
// 関数群の読み込み
require_once __DIR__ . '/../functions.php';

if (isSetupComplete($dbFile, $mailConfigFile)) {
    // セットアップが完了している場合
    $_SESSION['setup_message'] = "セットアップは既に完了しています。";
    header("Location: setup_message.php");
    exit;
} else {
    // セットアップが完了していない場合
    header("Location: setup.php");
    exit;
}

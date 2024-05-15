<?php
session_start();

// 関数群の読み込み
require_once __DIR__ . '/../functions.php';

if (isSetupComplete()) {
    // セットアップが完了している場合
    $_SESSION['setup_message'] = "セットアップは既に完了しています。";
    $_SESSION['next_action'] = "../admin";
    header("Location: setup_message.php");
    exit;
} else {
    // セットアップが完了していない場合
    header("Location: setup.php");
    exit;
}

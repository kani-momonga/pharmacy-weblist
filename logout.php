<?php
session_start();
require_once 'functions.php';

// セッションをクリアしてログアウト
session_unset();
session_destroy();

// フラッシュメッセージを設定
setFlashMessage("ログアウトしました。");

// `index.php` に遷移
header("Location: index.php");
exit;

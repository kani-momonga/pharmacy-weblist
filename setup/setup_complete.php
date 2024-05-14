<?php
session_start();
if (!isset($_SESSION['setup_complete'])) {
    header("Location: initial_setup.php");
    exit;
}

// setup.php、initial_setup.php、setup.sqlを削除する関数
function deleteSetupFiles() {
    $setupFiles = [
        __DIR__ . '/setup.php',
        __DIR__ . '/initial_setup.php',
        __DIR__ . '/setup.sql',
    ];

    foreach ($setupFiles as $file) {
        if (file_exists($file)) {
            if (!unlink($file)) {
                return false;
            }
        }
    }
    return true;
}

// ファイルの権限を600に設定
$dbFile = __DIR__ . '/../pharmacy.db';
$configFile = __DIR__ . '/../mail_config.json';

if (file_exists($dbFile)) {
    chmod($dbFile, 0600);
}

if (file_exists($configFile)) {
    chmod($configFile, 0600);
}

// setup.php、initial_setup.php、setup.sqlを削除
if (deleteSetupFiles()) {
    $_SESSION['setup_message'] = "セットアップが完了しました。セットアップスクリプトは削除されました。";
} else {
    $_SESSION['setup_message'] = "セットアップは完了しましたが、セットアップスクリプトの削除に失敗しました。";
}

unset($_SESSION['next_action']);
header("Location: setup_message.php");
exit;
?>

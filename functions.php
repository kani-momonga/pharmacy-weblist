<?php
require_once 'mail_functions.php';

// データベースに接続する関数
function connectDb() {
    try {
        // データベースを開く
        // データベースファイルが存在しない場合は新規に作成されます
        $db = new PDO('sqlite:' . __DIR__ . '/pharmacy.db');
        // データベースを操作するオプションを設定
        // PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        // エラー時に例外を投げる
        // これによってif文で異常を判定する必要がなくなります
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        die("データベース接続に失敗しました: " . $e->getMessage());
    }
}

// フラッシュメッセージを設定する関数
function setFlashMessage($message) {
    $_SESSION['flash_message'] = $message;
}

// ユーザー登録機能
function registerUser($username, $password, $email) {
    $db = connectDb();
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $isSuccess = false;
    try {
        // ユーザー名の重複チェック
        $stmt = $db->prepare("SELECT COUNT(*) FROM Users WHERE username = ?");
        $stmt->execute([$username]);
        $userCount = $stmt->fetchColumn();

        if ($userCount > 0) {
            return "このユーザー名は既に使用されています。";
        }

        $start = microtime(true);
        $insertSQL = "INSERT INTO Users (username, password, email, role) VALUES (:username, :password, :email, 'user')";
        if ($stmt = $db->prepare($insertSQL)) {
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            if ($stmt->execute()) {
                $end = microtime(true);
                error_log("User insertion took " . ($end - $start) . " seconds");
                $subject = "ユーザー登録確認";
                $message = "$username 様、\n\nご登録が完了しました。管理者の承認をお待ちください。";
                //sendMail($email, $subject, $message);
                $isSuccess = true;
            } else {
                die("ユーザー登録SQLが実行できませんでした。Code:002");
            }
        } else {
            die("ユーザー登録prepareSQLが実行できませんでした。Code:001");
        }

        return $isSuccess;
    } catch (PDOException $e) {
        $db->rollBack();
        return "データベースエラー: " . $e->getMessage();
    } catch (Exception $e) {
        return "一般エラー: " . $e->getMessage();
    }
}

// ユーザーログイン機能
function loginUser($username, $password) {
    $db = connectDb();
    try {
        $stmt = $db->prepare("SELECT * FROM Users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            if ($user['approved']) {
                //session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                return true;
            } else {
                return "アカウントがまだ承認されていません。";
            }
        } else {
            return "ユーザー名またはパスワードが無効です。";
        }
    } catch (PDOException $e) {
        return "エラー: " . $e->getMessage();
    }
}

// ユーザー承認機能
function approveUser($userId, $approved) {
    $db = connectDb();
    try {
        $stmt = $db->prepare("UPDATE Users SET approved = ? WHERE id = ?");
        $stmt->execute([$approved, $userId]);

        $stmt = $db->prepare("SELECT * FROM Users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $subject = "アカウント承認ステータス";
        $message = "あなたのアカウントは " . ($approved ? "承認されました。" : "承認されませんでした。");

        sendMail($user['email'], $subject, $message);
        return true;
    } catch (PDOException $e) {
        return "エラー: " . $e->getMessage();
    }
}

//ログインしているユーザーを取得する
function getLoggedinUser($db){
    if(empty($_SESSION['username'])){
        return false;
    }
    $username = $_SESSION['username'];
    $stmt = $db->prepare("SELECT * FROM Users WHERE username = ? AND approved = ?");
    $stmt->execute([$username,1]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(empty($user)){
        return false;
    }
    return $user;
}

// 薬局登録機能
function registerPharmacy($name, $address, $phone, $fax, $owner_id, $approved = 0) {
    $db = connectDb();
    try {
        $user = getLoggedinUser($db);
        if(!$user){
            return "ログインしているユーザーが正しくありません";
        }

        $stmt = $db->prepare("INSERT INTO Pharmacies (name, address, phone, fax, owner_id, approved) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $address, $phone, $fax, $owner_id, $approved]);

        $subject = "新しい薬局の登録";
        $message = "新しい薬局が登録され、承認待ちです。";

        sendMail($user['email'], $subject, $message);
        return true;
    } catch (PDOException $e) {
        return "エラー: " . $e->getMessage();
    }
}

// 薬局情報編集機能
function editPharmacy($id, $name, $address, $phone, $fax) {
    $db = connectDb();
    try {
        $user = getLoggedinUser($db);
        if(!$user){
            return "ログインしているユーザーが正しくありません";
        }
        $stmt = $db->prepare("UPDATE Pharmacies SET name = ?, address = ?, phone = ?, fax = ? WHERE id = ?");
        $stmt->execute([$name, $address, $phone, $fax, $id]);

        $subject = "薬局情報の更新";
        $message = "薬局の情報が更新されました。";

        sendMail($user['email'], $subject, $message);
        return true;
    } catch (PDOException $e) {
        return "エラー: " . $e->getMessage();
    }
}

// 薬局メタデータ登録機能
function addPharmacyMeta($pharmacy_id, $metakey, $subject, $value) {
    $db = connectDb();
    try {
        $stmt = $db->prepare("INSERT INTO PharmacyMeta (pharmacy_id, metakey, subject, value) VALUES (?, ?, ?, ?)");
        $stmt->execute([$pharmacy_id, $metakey, $subject, $value]);
        return true;
    } catch (PDOException $e) {
        return "エラー: " . $e->getMessage();
    }
}

// メタキー管理機能
function addMetaKey($metakey, $description) {
    $db = connectDb();
    try {
        $stmt = $db->prepare("INSERT INTO MetaKeys (metakey, description) VALUES (?, ?)");
        $stmt->execute([$metakey, $description]);
        return true;
    } catch (PDOException $e) {
        return "エラー: " . $e->getMessage();
    }
}

// メタキーの取得機能
function getMetaKeys() {
    $db = connectDb();
    try {
        $stmt = $db->query("SELECT * FROM MetaKeys");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

// セットアップが完了しているか確認する関数
function isSetupComplete() {
    // データベースファイルのパス
    $dbFile = __DIR__ . '/pharmacy.db';
    // メール設定ファイルのパス
    $mailConfigFile = __DIR__ . '/mail_config.json';
    
    // データベースファイルが存在しない場合、セットアップは完了していない
    if (!file_exists($dbFile)) {
        return false;
    }

    // メール設定ファイルが存在しない場合、セットアップは完了していない
    if (!file_exists($mailConfigFile)) {
        return false;
    }

    // データベース接続の確認とadminユーザーの存在確認
    try {
        $db = new PDO('sqlite:' . $dbFile);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->query("SELECT COUNT(*) FROM Users WHERE role = 'admin'");
        $adminCount = $stmt->fetchColumn();

        // adminユーザーが存在しない場合、セットアップは完了していない
        if ($adminCount == 0) {
            return false;
        }

    } catch (PDOException $e) {
        return false;
    }

    return true;
}

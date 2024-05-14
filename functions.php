<?php
require_once 'mail_functions.php';

function connectDb() {
    try {
        $db = new PDO('sqlite:' . __DIR__ . '/pharmacy.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

function setFlashMessage($message) {
    $_SESSION['flash_message'] = $message;
}

// ユーザー登録機能
function registerUser($username, $password, $email) {
    $db = connectDb();
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = $db->prepare("INSERT INTO Users (username, password, email, role, approved) VALUES (?, ?, ?, 'user', 0)");
        $stmt->execute([$username, $hashedPassword, $email]);

        $subject = "User Registration Confirmation";
        $message = "Dear $username,\n\nYour registration is successful. Please wait for admin approval.";

        sendMail($email, $subject, $message);
        return true;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
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
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                return true;
            } else {
                return "Your account is not approved yet.";
            }
        } else {
            return "Invalid username or password.";
        }
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
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

        $subject = "Account Approval Status";
        $message = "Your account has been " . ($approved ? "approved" : "disapproved") . ".";

        sendMail($user['email'], $subject, $message);
        return true;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

// 薬局登録機能
function registerPharmacy($name, $address, $phone, $email, $owner_id, $approved = 0) {
    $db = connectDb();
    try {
        $stmt = $db->prepare("INSERT INTO Pharmacies (name, address, phone, email, owner_id, approved) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $address, $phone, $email, $owner_id, $approved]);

        $subject = "New Pharmacy Registration";
        $message = "A new pharmacy has been registered and is pending approval.";

        sendMail($email, $subject, $message);
        return true;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

// 薬局情報編集機能
function editPharmacy($id, $name, $address, $phone, $email) {
    $db = connectDb();
    try {
        $stmt = $db->prepare("UPDATE Pharmacies SET name = ?, address = ?, phone = ?, email = ? WHERE id = ?");
        $stmt->execute([$name, $address, $phone, $email, $id]);

        $subject = "Pharmacy Information Updated";
        $message = "The information for the pharmacy has been updated.";

        sendMail($email, $subject, $message);
        return true;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
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
        return "Error: " . $e->getMessage();
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
        return "Error: " . $e->getMessage();
    }
}

function getMetaKeys() {
    $db = connectDb();
    try {
        $stmt = $db->query("SELECT * FROM MetaKeys");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

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
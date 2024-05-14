<?php
$title = "薬局追加";
include 'header.php';

require_once '../functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $owner_id = $_SESSION['user_id'];

    $result = registerPharmacy($name, $address, $phone, $email, $owner_id, 1);
    if ($result === true) {
        setFlashMessage("薬局が追加されました。");
        header("Location: pharmacy_add.php");
        exit;
    } else {
        echo $result;
    }
}
?>

<h1>薬局追加</h1>
<form method="POST" action="">
    <label for="name">薬局名:</label>
    <input type="text" name="name" required><br>
    <label for="address">住所:</label>
    <input type="text" name="address" required><br>
    <label for="phone">電話番号:</label>
    <input type="text" name="phone" required><br>
    <label for="email">メールアドレス:</label>
    <input type="email" name="email" required><br>
    <button type="submit">追加</button>
</form>
</body>
</html>

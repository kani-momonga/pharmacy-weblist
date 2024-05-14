<?php
require_once 'functions.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $owner_id = $_SESSION['user_id'];

    $result = registerPharmacy($name, $address, $phone, $email, $owner_id);
    if ($result === true) {
        echo "Pharmacy registration successful. Awaiting admin approval.";
    } else {
        echo $result;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>薬局情報を登録</title>
</head>
<body>
    <h1>Pharmacy Registration</h1>
    <form method="POST" action="">
        <label for="name">Pharmacy Name:</label>
        <input type="text" name="name" required><br>
        <label for="address">Address:</label>
        <input type="text" name="address" required><br>
        <label for="phone">Phone:</label>
        <input type="text" name="phone" required><br>
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>

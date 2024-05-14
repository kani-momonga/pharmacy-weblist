<?php
$title = "新規薬局登録";
include 'header.php';
require_once 'functions.php';

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
        setFlashMessage("薬局が登録されました。承認をお待ちください。");
        header("Location: user_profile.php");
        exit;
    } else {
        $error_message = $result;
    }
}
?>

<div class="container mt-4">
    <h1 class="mb-4">新規薬局登録</h1>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="name" class="form-label">薬局名</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">住所</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">電話番号</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="mb-3">
            <label for="fax" class="form-label">FAX番号</label>
            <input type="text" class="form-control" id="fax" name="fax" required>
        </div>
        <button type="submit" class="btn btn-success">登録</button>
    </form>
</div>

</body>
</html>

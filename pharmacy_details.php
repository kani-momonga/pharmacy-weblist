<?php
if (!isset($_GET['id'])) {
    die("Pharmacy ID is required.");
}

try {
    $db = new PDO('sqlite:pharmacy.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("SELECT * FROM Pharmacies WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $pharmacy = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pharmacy) {
        die("Pharmacy not found.");
    }

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Pharmacy Details</title>
</head>
<body>
    <h1><?= htmlspecialchars($pharmacy['name']) ?></h1>
    <p>Address: <?= htmlspecialchars($pharmacy['address']) ?></p>
    <p>Phone: <?= htmlspecialchars($pharmacy['phone']) ?></p>
    <p>Email: <?= htmlspecialchars($pharmacy['email']) ?></p>
</body>
</html>

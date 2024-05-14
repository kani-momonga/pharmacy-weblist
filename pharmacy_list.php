<?php
try {
    $db = new PDO('sqlite:pharmacy.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->query("SELECT * FROM Pharmacies");
    $pharmacies = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pharmacy List</title>
</head>
<body>
    <h1>Pharmacy List</h1>
    <ul>
        <?php foreach ($pharmacies as $pharmacy): ?>
            <li>
                <a href="pharmacy_details.php?id=<?= htmlspecialchars($pharmacy['id']) ?>">
                    <?= htmlspecialchars($pharmacy['name']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>

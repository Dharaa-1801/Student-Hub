<?php
require_once 'db.php';

// Using Prepared Statements to securely fetch data
$stmt = $pdo->prepare("SELECT * FROM events WHERE category = ?");
$stmt->execute(['Tech']);
$techEvents = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 8 - DB Connection Test</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="font-family: Arial, sans-serif; padding: 2rem; background: #f8fafc;">
    <h2 style="color: #3b0764;">PDO Connectivity & Prepared Statements</h2>
    <hr>
    
    <div style="background: #d1e7dd; color: #0f5132; padding: 12px; border-radius: 6px; margin: 1rem 0; font-weight: bold;">
        Database Connection Successful via PDO!
    </div>

    <h3>Fetched Tech Events (Using Prepared Statement):</h3>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; background: white; border-collapse: collapse;">
        <thead>
            <tr style="background: #e2e8f0;">
                <th>ID</th>
                <th>Event Title</th>
                <th>Category</th>
                <th>Date</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($techEvents) > 0): ?>
                <?php foreach ($techEvents as $event): ?>
                    <tr>
                        <td><?= htmlspecialchars($event['id']) ?></td>
                        <td><?= htmlspecialchars($event['title']) ?></td>
                        <td><?= htmlspecialchars($event['category']) ?></td>
                        <td><?= htmlspecialchars($event['event_date']) ?></td>
                        <td><?= htmlspecialchars($event['description']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align: center;">No records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php
$db = require 'database.php';

// Fetch all items
$stmt = $db->query("SELECT * FROM items ORDER BY id DESC");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trucking Service - Item Master</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Item Master (Trucking Services)</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">Item added successfully!</div>
        <?php endif; ?>

        <nav>
            <a href="add.php" class="btn primary">Add New Item</a>
        </nav>

        <section class="item-list">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Get In</th>
                        <th>Get Out</th>
                        <th>Destination</th>
                        <th>Container</th>
                        <th>Vendor</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="7">No items found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['id']); ?></td>
                                <td><?php echo htmlspecialchars($item['get_in']); ?></td>
                                <td><?php echo htmlspecialchars($item['get_out']); ?></td>
                                <td><?php echo htmlspecialchars($item['destination']); ?></td>
                                <td><?php echo htmlspecialchars($item['container']); ?></td>
                                <td><?php echo htmlspecialchars($item['vendor']); ?></td>
                                <td><?php echo htmlspecialchars(number_format($item['price'], 2)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>

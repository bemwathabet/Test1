<?php
$db = require 'database.php';

// Fetch all items with joined names for locations and destinations
$query = "SELECT i.*,
                 t_in.name as get_in_name,
                 t_out.name as get_out_name,
                 d.name as destination_name
          FROM items i
          JOIN terminals t_in ON i.get_in_id = t_in.id
          JOIN terminals t_out ON i.get_out_id = t_out.id
          JOIN destinations d ON i.destination_id = d.id
          ORDER BY i.id DESC";
$stmt = $db->query($query);
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
        <?php include 'nav.php'; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">Item added successfully!</div>
        <?php endif; ?>

        <nav class="actions">
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
                                <td><?php echo htmlspecialchars($item['get_in_name']); ?></td>
                                <td><?php echo htmlspecialchars($item['get_out_name']); ?></td>
                                <td><?php echo htmlspecialchars($item['destination_name']); ?></td>
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

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
    <title>Trucking Service Items</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Trucking Service Items</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">Item added successfully!</div>
        <?php endif; ?>

        <section class="add-item">
            <h2>Add New Item</h2>
            <form action="add_item.php" method="POST">
                <div class="form-group">
                    <label for="get_in">Get In:</label>
                    <input type="text" id="get_in" name="get_in" required>
                </div>
                <div class="form-group">
                    <label for="get_out">Get Out:</label>
                    <input type="text" id="get_out" name="get_out" required>
                </div>
                <div class="form-group">
                    <label for="destination">Destination:</label>
                    <input type="text" id="destination" name="destination" required>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" step="0.01" required>
                </div>
                <button type="submit">Add Item</button>
            </form>
        </section>

        <section class="item-list">
            <h2>Recorded Items</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Get In</th>
                        <th>Get Out</th>
                        <th>Destination</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="5">No items found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['id']); ?></td>
                                <td><?php echo htmlspecialchars($item['get_in']); ?></td>
                                <td><?php echo htmlspecialchars($item['get_out']); ?></td>
                                <td><?php echo htmlspecialchars($item['destination']); ?></td>
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

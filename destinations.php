<?php
$db = require 'database.php';

// Fetch all destinations
$stmt = $db->query("SELECT * FROM destinations ORDER BY name ASC");
$destinations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destinations</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Destinations</h1>
        <?php include 'nav.php'; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">Destination added successfully!</div>
        <?php endif; ?>

        <section class="add-new">
            <h2>Add New Destination</h2>
            <form action="add_destination.php" method="POST">
                <div class="form-group">
                    <label for="name">Destination Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <button type="submit" class="btn primary">Add Destination</button>
            </form>
        </section>

        <section class="list">
            <h2>Existing Destinations</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($destinations)): ?>
                        <tr>
                            <td colspan="2">No destinations found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($destinations as $destination): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($destination['id']); ?></td>
                                <td><?php echo htmlspecialchars($destination['name']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>

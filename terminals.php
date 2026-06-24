<?php
$db = require 'database.php';

// Fetch all terminals
$stmt = $db->query("SELECT * FROM terminals ORDER BY name ASC");
$terminals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get in Get out (Terminals)</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Get in Get out (Terminals)</h1>
        <?php include 'nav.php'; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">Terminal added successfully!</div>
        <?php endif; ?>

        <section class="add-new">
            <h2>Add New Location</h2>
            <form action="add_terminal.php" method="POST">
                <div class="form-group">
                    <label for="name">Location Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <button type="submit" class="btn primary">Add Location</button>
            </form>
        </section>

        <section class="list">
            <h2>Existing Locations</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($terminals)): ?>
                        <tr>
                            <td colspan="2">No locations found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($terminals as $terminal): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($terminal['id']); ?></td>
                                <td><?php echo htmlspecialchars($terminal['name']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>

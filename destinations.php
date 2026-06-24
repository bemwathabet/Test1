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
    <title>LogiTrack Enterprise - Destinations</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-wrapper">
        <?php include 'header_component.php'; ?>
        <?php include 'nav.php'; ?>

        <main class="main-content">
            <header class="page-header">
                <div class="page-title">
                    <h1>Destinations Management</h1>
                </div>
            </header>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">Destination added successfully!</div>
            <?php endif; ?>

            <div class="card add-new">
                <h2 class="card-title">Add New Destination</h2>
                <form action="add_destination.php" method="POST">
                    <div class="form-group">
                        <label for="name">Destination Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter final destination point..." required>
                    </div>
                    <button type="submit" class="btn btn-primary">Register Destination</button>
                </form>
            </div>

            <div class="card">
                <h2 class="card-title">Registered Destinations</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Destination Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($destinations)): ?>
                                <tr>
                                    <td colspan="2">No destinations registered in the system.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($destinations as $destination): ?>
                                    <tr>
                                        <td>#<?php echo htmlspecialchars($destination['id']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($destination['name']); ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

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
    <title>EGLTRUCK - Destinations</title>
    <link rel="stylesheet" href="style.css">
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
                <div class="page-actions">
                    <a href="export.php?type=destinations" class="btn">Export CSV</a>
                    <a href="import.php?type=destinations" class="btn">Import CSV</a>
                </div>
            </header>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">Destination added/updated successfully!</div>
            <?php endif; ?>
            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-danger">Destination deleted.</div>
            <?php endif; ?>

            <div class="card add-new">
                <h2 class="card-title">Add New Destination</h2>
                <form action="add_destination.php" method="POST">
                    <div class="form-group">
                        <label for="name">Destination Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter destination city/port..." required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Destination</button>
                </form>
            </div>

            <div class="card">
                <h2 class="card-title">Active Destinations</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Destination Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($destinations)): ?>
                                <tr>
                                    <td colspan="3">No destinations found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($destinations as $dest): ?>
                                    <tr>
                                        <td>#<?php echo htmlspecialchars($dest['id']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($dest['name']); ?></strong></td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="edit_lookup.php?type=destinations&id=<?php echo $dest['id']; ?>" class="action-link edit">Edit</a>
                                                <a href="delete.php?type=destinations&id=<?php echo $dest['id']; ?>" class="action-link delete" onclick="return confirm('Delete this destination?')">Delete</a>
                                            </div>
                                        </td>
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

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
    <title>LogiTrack Enterprise - Terminals</title>
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
                    <h1>Terminals Management</h1>
                </div>
                <div class="page-actions">
                    <a href="export.php?type=terminals" class="btn">Export CSV</a>
                    <a href="import.php?type=terminals" class="btn">Import CSV</a>
                </div>
            </header>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">Terminal location added/updated successfully!</div>
            <?php endif; ?>
            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-danger">Terminal location deleted.</div>
            <?php endif; ?>

            <div class="card add-new">
                <h2 class="card-title">Add New Terminal</h2>
                <form action="add_terminal.php" method="POST">
                    <div class="form-group">
                        <label for="name">Location Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter terminal or port name..." required>
                    </div>
                    <button type="submit" class="btn btn-primary">Register Terminal</button>
                </form>
            </div>

            <div class="card">
                <h2 class="card-title">Registered Terminal Locations</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Terminal Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($terminals)): ?>
                                <tr>
                                    <td colspan="3">No terminal locations registered in the system.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($terminals as $terminal): ?>
                                    <tr>
                                        <td>#<?php echo htmlspecialchars($terminal['id']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($terminal['name']); ?></strong></td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="edit_lookup.php?type=terminals&id=<?php echo $terminal['id']; ?>" class="action-link edit">Edit</a>
                                                <a href="delete.php?type=terminals&id=<?php echo $terminal['id']; ?>" class="action-link delete" onclick="return confirm('Delete this terminal?')">Delete</a>
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

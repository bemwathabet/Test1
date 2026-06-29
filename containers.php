<?php
$db = require 'database.php';

// Fetch all containers
$stmt = $db->query("SELECT * FROM containers ORDER BY reference ASC");
$containers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EGLTRUCK - Containers</title>
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
                    <h1>Containers Management</h1>
                </div>
                <div class="page-actions">
                    <a href="export.php?type=containers" class="btn">Export CSV</a>
                    <a href="import.php?type=containers" class="btn">Import CSV</a>
                </div>
            </header>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">Container reference added/updated successfully!</div>
            <?php endif; ?>
            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-danger">Container reference deleted.</div>
            <?php endif; ?>

            <div class="card add-new">
                <h2 class="card-title">Add New Container Type</h2>
                <form action="add_container.php" method="POST">
                    <div class="form-group">
                        <label for="reference">Reference / Type</label>
                        <input type="text" id="reference" name="reference" placeholder="e.g. 40ft High Cube" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Container</button>
                </form>
            </div>

            <div class="card">
                <h2 class="card-title">Container Fleet Types</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Reference</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($containers)): ?>
                                <tr>
                                    <td colspan="3">No containers found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($containers as $cont): ?>
                                    <tr>
                                        <td>#<?php echo htmlspecialchars($cont['id']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($cont['reference']); ?></strong></td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="edit_lookup.php?type=containers&id=<?php echo $cont['id']; ?>" class="action-link edit">Edit</a>
                                                <a href="delete.php?type=containers&id=<?php echo $cont['id']; ?>" class="action-link delete" onclick="return confirm('Delete this container type?')">Delete</a>
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

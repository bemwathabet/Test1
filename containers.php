<?php
$db = require 'database.php';
$stmt = $db->query("SELECT * FROM containers ORDER BY reference ASC");
$containers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogiTrack Enterprise - Containers</title>
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
                    <h1>Containers Management</h1>
                </div>
            </header>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">Container reference added successfully!</div>
            <?php endif; ?>

            <div class="card add-new">
                <h2 class="card-title">Add New Container</h2>
                <form action="add_container.php" method="POST">
                    <div class="form-group">
                        <label for="reference">Container Reference</label>
                        <input type="text" id="reference" name="reference" placeholder="e.g. MSKU1234567" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Register Container</button>
                </form>
            </div>

            <div class="card">
                <h2 class="card-title">Registered Container Units</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Container Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($containers)): ?>
                                <tr>
                                    <td colspan="2">No containers registered.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($containers as $c): ?>
                                    <tr>
                                        <td>#<?php echo htmlspecialchars($c['id']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($c['reference']); ?></strong></td>
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

<?php
$db = require 'database.php';
$stmt = $db->query("SELECT * FROM vendors ORDER BY name ASC");
$vendors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogiTrack Enterprise - Vendors</title>
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
                    <h1>Vendors Management</h1>
                </div>
            </header>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">Vendor added successfully!</div>
            <?php endif; ?>

            <div class="card add-new">
                <h2 class="card-title">Add New Vendor</h2>
                <form action="add_vendor.php" method="POST">
                    <div class="form-group">
                        <label for="name">Vendor Name</label>
                        <input type="text" id="name" name="name" placeholder="e.g. Maersk" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Register Vendor</button>
                </form>
            </div>

            <div class="card">
                <h2 class="card-title">Registered Freight Vendors</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vendor Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($vendors)): ?>
                                <tr>
                                    <td colspan="2">No vendors registered.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($vendors as $v): ?>
                                    <tr>
                                        <td>#<?php echo htmlspecialchars($v['id']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($v['name']); ?></strong></td>
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

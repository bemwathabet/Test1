<?php
$db = require 'database.php';

// Fetch all vendors
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
                <div class="page-actions">
                    <a href="export.php?type=vendors" class="btn">Export CSV</a>
                    <a href="import.php?type=vendors" class="btn">Import CSV</a>
                </div>
            </header>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">Vendor added/updated successfully!</div>
            <?php endif; ?>
            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-danger">Vendor deleted.</div>
            <?php endif; ?>

            <div class="card add-new">
                <h2 class="card-title">Add New Vendor</h2>
                <form action="add_vendor.php" method="POST">
                    <div class="form-group">
                        <label for="name">Vendor Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter company name..." required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Vendor</button>
                </form>
            </div>

            <div class="card">
                <h2 class="card-title">Approved Vendors</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vendor Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($vendors)): ?>
                                <tr>
                                    <td colspan="3">No vendors found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($vendors as $vendor): ?>
                                    <tr>
                                        <td>#<?php echo htmlspecialchars($vendor['id']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($vendor['name']); ?></strong></td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="edit_lookup.php?type=vendors&id=<?php echo $vendor['id']; ?>" class="action-link edit">Edit</a>
                                                <a href="delete.php?type=vendors&id=<?php echo $vendor['id']; ?>" class="action-link delete" onclick="return confirm('Delete this vendor?')">Delete</a>
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

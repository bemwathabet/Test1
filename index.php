<?php
$db = require 'database.php';

// Fetch all items with joined names for all relational fields
$query = "SELECT i.*,
                 t_in.name as get_in_name,
                 t_out.name as get_out_name,
                 d.name as destination_name,
                 c.reference as container_ref,
                 v.name as vendor_name
          FROM items i
          JOIN terminals t_in ON i.get_in_id = t_in.id
          JOIN terminals t_out ON i.get_out_id = t_out.id
          JOIN destinations d ON i.destination_id = d.id
          JOIN containers c ON i.container_id = c.id
          JOIN vendors v ON i.vendor_id = v.id
          ORDER BY i.id DESC";
$stmt = $db->query($query);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogiTrack Enterprise - Item Master</title>
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
                    <h1>Item Master</h1>
                    <p>Overview of all active shipment routes and pricing</p>
                </div>
                <div class="page-actions">
                    <a href="export.php?type=items" class="btn">Export CSV</a>
                    <a href="import.php?type=items" class="btn">Import CSV</a>
                    <a href="add.php" class="btn btn-primary">Add New Item</a>
                </div>
            </header>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">Shipment record added successfully!</div>
            <?php endif; ?>
            <?php if (isset($_GET['updated'])): ?>
                <div class="alert alert-success">Shipment record updated successfully!</div>
            <?php endif; ?>
            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-danger">Record deleted successfully.</div>
            <?php endif; ?>

            <div class="card">
                <h2 class="card-title">Trucking Service Items</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Get In</th>
                                <th>Get Out</th>
                                <th>Destination</th>
                                <th>Container</th>
                                <th>Vendor</th>
                                <th>Price (USD)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($items)): ?>
                                <tr>
                                    <td colspan="8">No shipment records found in the master list.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><strong>#<?php echo htmlspecialchars($item['id']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($item['get_in_name']); ?></td>
                                        <td><?php echo htmlspecialchars($item['get_out_name']); ?></td>
                                        <td><?php echo htmlspecialchars($item['destination_name']); ?></td>
                                        <td><span class="badge"><?php echo htmlspecialchars($item['container_ref']); ?></span></td>
                                        <td><?php echo htmlspecialchars($item['vendor_name']); ?></td>
                                        <td><strong>$<?php echo htmlspecialchars(number_format($item['price'], 2)); ?></strong></td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="edit_item.php?id=<?php echo $item['id']; ?>" class="action-link edit">Edit</a>
                                                <a href="delete.php?type=items&id=<?php echo $item['id']; ?>" class="action-link delete" onclick="return confirm('Delete this shipment?')">Delete</a>
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

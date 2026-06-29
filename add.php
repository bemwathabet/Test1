<?php
$db = require 'database.php';

// Fetch lookups for the dropdowns
$terminals = $db->query("SELECT * FROM terminals ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$destinations = $db->query("SELECT * FROM destinations ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$containers = $db->query("SELECT * FROM containers ORDER BY reference ASC")->fetchAll(PDO::FETCH_ASSOC);
$vendors = $db->query("SELECT * FROM vendors ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EGLTRUCK - New Shipment</title>
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
                    <h1>Register New Shipment</h1>
                </div>
            </header>

            <div class="card add-item-full">
                <h2 class="card-title">Shipment Details</h2>
                <form action="add_item.php" method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="get_in_id">Get In Point</label>
                            <select id="get_in_id" name="get_in_id" required>
                                <option value="">Select origin...</option>
                                <?php foreach ($terminals as $t): ?>
                                    <option value="<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="get_out_id">Get Out Point</label>
                            <select id="get_out_id" name="get_out_id" required>
                                <option value="">Select exit point...</option>
                                <?php foreach ($terminals as $t): ?>
                                    <option value="<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="destination_id">Final Destination</label>
                            <select id="destination_id" name="destination_id" required>
                                <option value="">Select destination...</option>
                                <?php foreach ($destinations as $d): ?>
                                    <option value="<?php echo $d['id']; ?>"><?php echo htmlspecialchars($d['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="container_id">Container Reference</label>
                            <select id="container_id" name="container_id" required>
                                <option value="">Select container...</option>
                                <?php foreach ($containers as $c): ?>
                                    <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['reference']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="vendor_id">Vendor Name</label>
                            <select id="vendor_id" name="vendor_id" required>
                                <option value="">Select vendor...</option>
                                <?php foreach ($vendors as $v): ?>
                                    <option value="<?php echo $v['id']; ?>"><?php echo htmlspecialchars($v['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="price">Agreed Price</label>
                            <div style="display: flex; gap: 8px;">
                                <select name="currency" style="width: 80px;" required>
                                    <option value="EGP" selected>EGP</option>
                                    <option value="$">$</option>
                                </select>
                                <input type="number" id="price" name="price" step="0.01" placeholder="0.00" required style="flex: 1;">
                            </div>
                        </div>
                        <div class="form-group full-width">
                            <button type="submit" class="btn btn-primary">Create Shipment Record</button>
                            <a href="index.php" class="btn">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>

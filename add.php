<?php
$db = require 'database.php';

// Fetch terminals and destinations for the dropdowns
$terminals = $db->query("SELECT * FROM terminals ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$destinations = $db->query("SELECT * FROM destinations ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Trucking Service Item</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Add New Trucking Service Item</h1>
        <?php include 'nav.php'; ?>

        <section class="add-item-full">
            <form action="add_item.php" method="POST">
                <div class="form-group">
                    <label for="get_in_id">Get In:</label>
                    <select id="get_in_id" name="get_in_id" required>
                        <option value="">-- Select Location --</option>
                        <?php foreach ($terminals as $t): ?>
                            <option value="<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="get_out_id">Get Out:</label>
                    <select id="get_out_id" name="get_out_id" required>
                        <option value="">-- Select Location --</option>
                        <?php foreach ($terminals as $t): ?>
                            <option value="<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="destination_id">Destination:</label>
                    <select id="destination_id" name="destination_id" required>
                        <option value="">-- Select Destination --</option>
                        <?php foreach ($destinations as $d): ?>
                            <option value="<?php echo $d['id']; ?>"><?php echo htmlspecialchars($d['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="container">Container:</label>
                    <input type="text" id="container" name="container" required>
                </div>
                <div class="form-group">
                    <label for="vendor">Vendor:</label>
                    <input type="text" id="vendor" name="vendor" required>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" step="0.01" required>
                </div>
                <button type="submit" class="btn primary">Save Item</button>
            </form>
        </section>
    </div>
</body>
</html>

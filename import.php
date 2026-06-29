<?php
$db = require 'database.php';

$type = $_GET['type'] ?? 'items';
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    if ($_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['csv_file']['tmp_name'];
        if (($handle = fopen($file, "r")) !== FALSE) {
            // Skip header
            $header = fgetcsv($handle, 1000, ",");

            $db->beginTransaction();
            try {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (empty($data)) continue;

                    switch ($type) {
                        case 'terminals':
                            $name = $data[0] ?? ($data[1] ?? ''); // Allow ID, Name or just Name
                            if (count($data) > 1 && is_numeric($data[0])) $name = $data[1];
                            if ($name) {
                                $stmt = $db->prepare("INSERT OR IGNORE INTO terminals (name) VALUES (?)");
                                $stmt->execute([$name]);
                            }
                            break;
                        case 'destinations':
                            $name = $data[0] ?? ($data[1] ?? '');
                            if (count($data) > 1 && is_numeric($data[0])) $name = $data[1];
                            if ($name) {
                                $stmt = $db->prepare("INSERT OR IGNORE INTO destinations (name) VALUES (?)");
                                $stmt->execute([$name]);
                            }
                            break;
                        case 'containers':
                            $ref = $data[0] ?? ($data[1] ?? '');
                            if (count($data) > 1 && is_numeric($data[0])) $ref = $data[1];
                            if ($ref) {
                                $stmt = $db->prepare("INSERT OR IGNORE INTO containers (reference) VALUES (?)");
                                $stmt->execute([$ref]);
                            }
                            break;
                        case 'vendors':
                            $name = $data[0] ?? ($data[1] ?? '');
                            if (count($data) > 1 && is_numeric($data[0])) $name = $data[1];
                            if ($name) {
                                $stmt = $db->prepare("INSERT OR IGNORE INTO vendors (name) VALUES (?)");
                                $stmt->execute([$name]);
                            }
                            break;
                        case 'items':
                            // Expected: get_in, get_out, destination, container, vendor, price
                            // We need to map names to IDs.
                            if (count($data) < 6) continue 2;

                            $get_in_name = $data[1] ?? '';
                            $get_out_name = $data[2] ?? '';
                            $dest_name = $data[3] ?? '';
                            $cont_ref = $data[4] ?? '';
                            $vend_name = $data[5] ?? '';
                            $price = $data[6] ?? 0;
                            $currency = $data[7] ?? 'EGP';

                            // Helper function to get or create ID
                            $get_id = function($table, $col, $val) use ($db) {
                                $stmt = $db->prepare("SELECT id FROM $table WHERE $col = ?");
                                $stmt->execute([$val]);
                                $row = $stmt->fetch();
                                if ($row) return $row['id'];

                                $stmt = $db->prepare("INSERT INTO $table ($col) VALUES (?)");
                                $stmt->execute([$val]);
                                return $db->lastInsertId();
                            };

                            $get_in_id = $get_id('terminals', 'name', $get_in_name);
                            $get_out_id = $get_id('terminals', 'name', $get_out_name);
                            $dest_id = $get_id('destinations', 'name', $dest_name);
                            $cont_id = $get_id('containers', 'reference', $cont_ref);
                            $vend_id = $get_id('vendors', 'name', $vend_name);

                            $stmt = $db->prepare("INSERT INTO items (get_in_id, get_out_id, destination_id, container_id, vendor_id, price, currency) VALUES (?, ?, ?, ?, ?, ?, ?)");
                            $stmt->execute([$get_in_id, $get_out_id, $dest_id, $cont_id, $vend_id, $price, $currency]);
                            break;
                    }
                }
                $db->commit();
                $message = "Import successful!";
            } catch (Exception $e) {
                $db->rollBack();
                $error = "Import failed: " . $e->getMessage();
            }
            fclose($handle);
        }
    } else {
        $error = "Error uploading file.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EGLTRUCK - Import Data</title>
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
                    <h1>Import <?php echo ucfirst($type); ?></h1>
                </div>
            </header>

            <div class="card">
                <?php if ($message): ?>
                    <div class="alert alert-success" style="padding: 1rem; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 1rem;">
                        <?php echo $message; ?>
                        <a href="<?php echo ($type == 'items' ? 'index.php' : $type . '.php'); ?>" style="margin-left: 1rem; font-weight: bold;">View Results</a>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger" style="padding: 1rem; background: #f8d7da; color: #721c24; border-radius: 4px; margin-bottom: 1rem;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form action="import.php?type=<?php echo $type; ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="csv_file">Select CSV File</label>
                        <input type="file" id="csv_file" name="csv_file" accept=".csv" required>
                    </div>
                    <div class="form-group" style="margin-top: 1rem;">
                        <p><small>Note: The CSV should have a header row. For items, use the format exported by the system.</small></p>
                        <button type="submit" class="btn btn-primary">Upload and Import</button>
                        <a href="<?php echo ($type == 'items' ? 'index.php' : $type . '.php'); ?>" class="btn">Back</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>

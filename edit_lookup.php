<?php
$db = require 'database.php';

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? 0;

$valid_types = [
    'terminals' => ['table' => 'terminals', 'title' => 'Edit Terminal Location', 'field' => 'name'],
    'destinations' => ['table' => 'destinations', 'title' => 'Edit Destination', 'field' => 'name'],
    'containers' => ['table' => 'containers', 'title' => 'Edit Container', 'field' => 'reference'],
    'vendors' => ['table' => 'vendors', 'title' => 'Edit Vendor', 'field' => 'name']
];

if (!isset($valid_types[$type]) || $id <= 0) {
    die("Invalid request.");
}

$config = $valid_types[$type];
$table = $config['table'];
$field = $config['field'];

$stmt = $db->prepare("SELECT * FROM $table WHERE id = :id");
$stmt->execute([':id' => $id]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$record) {
    die("Record not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogiTrack Enterprise - <?php echo $config['title']; ?></title>
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
                    <h1><?php echo $config['title']; ?></h1>
                </div>
            </header>

            <div class="card add-new">
                <h2 class="card-title">Update Information</h2>
                <form action="update_lookup.php" method="POST">
                    <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

                    <div class="form-group">
                        <label for="value">Value Name / Reference</label>
                        <input type="text" id="value" name="value" value="<?php echo htmlspecialchars($record[$field]); ?>" required>
                    </div>

                    <div class="actions">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="<?php echo $table; ?>.php" class="btn">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>

<?php
$db = require 'database.php';

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? 0;

if (empty($type) || $id <= 0) {
    die("Invalid request parameters.");
}

$valid_types = [
    'items' => ['table' => 'items', 'redirect' => 'index.php'],
    'terminals' => ['table' => 'terminals', 'redirect' => 'terminals.php'],
    'destinations' => ['table' => 'destinations', 'redirect' => 'destinations.php'],
    'containers' => ['table' => 'containers', 'redirect' => 'containers.php'],
    'vendors' => ['table' => 'vendors', 'redirect' => 'vendors.php']
];

if (!isset($valid_types[$type])) {
    die("Invalid entity type.");
}

$config = $valid_types[$type];
$table = $config['table'];
$redirect = $config['redirect'];

try {
    $stmt = $db->prepare("DELETE FROM $table WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header("Location: $redirect?deleted=1");
    exit;
} catch (PDOException $e) {
    // If foreign key violation (SQLite code 19 for constraint)
    if ($e->getCode() == '23000' || strpos($e->getMessage(), 'FOREIGN KEY constraint failed') !== false) {
        die("Error: This record cannot be deleted because it is being used by other records (e.g. shipments). Please delete those first.");
    }
    die("Error deleting record: " . $e->getMessage());
}
?>

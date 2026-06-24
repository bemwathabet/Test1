<?php
$db = require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';
    $id = $_POST['id'] ?? 0;
    $value = $_POST['value'] ?? '';

    $valid_types = [
        'terminals' => ['table' => 'terminals', 'field' => 'name'],
        'destinations' => ['table' => 'destinations', 'field' => 'name'],
        'containers' => ['table' => 'containers', 'field' => 'reference'],
        'vendors' => ['table' => 'vendors', 'field' => 'name']
    ];

    if (!isset($valid_types[$type]) || $id <= 0 || empty($value)) {
        die("Invalid update request.");
    }

    $config = $valid_types[$type];
    $table = $config['table'];
    $field = $config['field'];

    try {
        $stmt = $db->prepare("UPDATE $table SET $field = :val WHERE id = :id");
        $stmt->execute([':val' => $value, ':id' => $id]);
        header("Location: $table.php?success=1");
        exit;
    } catch (PDOException $e) {
        die("Error updating record: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit;
}
?>

<?php
$db = require 'database.php';

$type = $_GET['type'] ?? 'items';

$filename = "logitrack_" . $type . "_" . date('Y-m-d') . ".csv";

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

switch ($type) {
    case 'terminals':
        fputcsv($output, ['ID', 'Name']);
        $rows = $db->query("SELECT id, name FROM terminals ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 'destinations':
        fputcsv($output, ['ID', 'Name']);
        $rows = $db->query("SELECT id, name FROM destinations ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 'containers':
        fputcsv($output, ['ID', 'Reference']);
        $rows = $db->query("SELECT id, reference FROM containers ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 'vendors':
        fputcsv($output, ['ID', 'Name']);
        $rows = $db->query("SELECT id, name FROM vendors ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 'items':
    default:
        fputcsv($output, ['ID', 'Get In', 'Get Out', 'Destination', 'Container', 'Vendor', 'Price']);
        $query = "SELECT
                    i.id,
                    tin.name as get_in,
                    tout.name as get_out,
                    d.name as destination,
                    c.reference as container,
                    v.name as vendor,
                    i.price
                  FROM items i
                  JOIN terminals tin ON i.get_in_id = tin.id
                  JOIN terminals tout ON i.get_out_id = tout.id
                  JOIN destinations d ON i.destination_id = d.id
                  JOIN containers c ON i.container_id = c.id
                  JOIN vendors v ON i.vendor_id = v.id
                  ORDER BY i.id DESC";
        $rows = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
        break;
}

foreach ($rows as $row) {
    fputcsv($output, $row);
}

fclose($output);
exit;
?>

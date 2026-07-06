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
        fputcsv($output, ['ID', 'Get In', 'Get Out', 'Destination', 'Container', 'Vendor', 'Price', 'Currency']);

        $s_get_in = $_GET['s_get_in'] ?? '';
        $s_get_out = $_GET['s_get_out'] ?? '';
        $s_dest = $_GET['s_dest'] ?? '';
        $s_container = $_GET['s_container'] ?? '';
        $s_vendor = $_GET['s_vendor'] ?? '';

        $where_clauses = [];
        $params = [];

        if (!empty($s_get_in)) {
            $where_clauses[] = "tin.name LIKE ?";
            $params[] = "%$s_get_in%";
        }
        if (!empty($s_get_out)) {
            $where_clauses[] = "tout.name LIKE ?";
            $params[] = "%$s_get_out%";
        }
        if (!empty($s_dest)) {
            $where_clauses[] = "d.name LIKE ?";
            $params[] = "%$s_dest%";
        }
        if (!empty($s_container)) {
            $where_clauses[] = "c.reference LIKE ?";
            $params[] = "%$s_container%";
        }
        if (!empty($s_vendor)) {
            $where_clauses[] = "v.name LIKE ?";
            $params[] = "%$s_vendor%";
        }

        $query = "SELECT
                    i.id,
                    tin.name as get_in,
                    tout.name as get_out,
                    d.name as destination,
                    c.reference as container,
                    v.name as vendor,
                    i.price,
                    i.currency
                  FROM items i
                  JOIN terminals tin ON i.get_in_id = tin.id
                  JOIN terminals tout ON i.get_out_id = tout.id
                  JOIN destinations d ON i.destination_id = d.id
                  JOIN containers c ON i.container_id = c.id
                  JOIN vendors v ON i.vendor_id = v.id";

        if (!empty($where_clauses)) {
            $query .= " WHERE " . implode(" AND ", $where_clauses);
        }

        $query .= " ORDER BY i.id DESC";

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        break;
}

foreach ($rows as $row) {
    fputcsv($output, $row);
}

fclose($output);
exit;
?>

<?php
$db = require 'database.php';

// Fetch all terminals for the "Gate In" filter
$terminals = $db->query("SELECT * FROM terminals ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Selected Gate In
$selected_gate_in = $_GET['gate_in_id'] ?? ($terminals[0]['id'] ?? 0);

// Fetch all vendors
$vendors = $db->query("SELECT * FROM vendors ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$selected_vendor = isset($_GET['vendor_id']) ? (int)$_GET['vendor_id'] : 0;

// Fetch all unique containers (Columns)
$containers = $db->query("SELECT * FROM containers ORDER BY reference ASC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all unique destinations (Rows)
$destinations = $db->query("SELECT * FROM destinations ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch pricing matrix for selected Gate In
$matrix_data = [];
$report_rows = []; // To keep track of unique combinations of (Destination, Vendor)

if ($selected_gate_in > 0) {
    $sql = "SELECT i.destination_id, i.container_id, i.price, i.currency, i.vendor_id, v.name as vendor_name, d.name as dest_name
            FROM items i
            JOIN vendors v ON i.vendor_id = v.id
            JOIN destinations d ON i.destination_id = d.id
            WHERE i.get_in_id = ?";

    $params = [$selected_gate_in];

    if ($selected_vendor > 0) {
        $sql .= " AND i.vendor_id = ?";
        $params[] = $selected_vendor;
    }

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        // Use a unique key for the row (Destination ID or Destination_Vendor combination)
        $row_key = ($selected_vendor > 0) ? $row['destination_id'] : $row['destination_id'] . '_' . $row['vendor_id'];

        if (!isset($report_rows[$row_key])) {
            $report_rows[$row_key] = [
                'dest_name' => $row['dest_name'],
                'vendor_name' => $row['vendor_name']
            ];
        }

        $matrix_data[$row_key][$row['container_id']] = $row['currency'] . ' ' . number_format($row['price'], 2);
    }

    // Sort rows alphabetically by destination, then vendor
    uasort($report_rows, function($a, $b) {
        $cmp = strcmp($a['dest_name'], $b['dest_name']);
        if ($cmp === 0) {
            return strcmp($a['vendor_name'], $b['vendor_name']);
        }
        return $cmp;
    });
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EGLTRUCK - Pricing Report</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .report-filter {
            background: #fff;
            padding: 16px 24px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: var(--shadow);
        }
        .report-filter select {
            width: 300px;
        }
        .pivot-table-card {
            overflow-x: auto;
        }
        .pivot-table th {
            white-space: nowrap;
            text-align: center;
            background: #f1f5f9;
        }
        .pivot-table td {
            text-align: center;
            border-right: 1px solid #f1f5f9;
        }
        .pivot-table td:first-child {
            text-align: left;
            background: #f8fafc;
            font-weight: 600;
            position: sticky;
            left: 0;
            z-index: 10;
        }
        .price-found {
            color: var(--primary-color);
            font-weight: 700;
        }
        .price-empty {
            color: #cbd5e1;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        <?php include 'header_component.php'; ?>
        <?php include 'nav.php'; ?>

        <main class="main-content">
            <header class="page-header">
                <div class="page-title">
                    <h1>Pricing Analysis Report</h1>
                    <p>Cross-referenced pricing matrix by Destination and Container Type</p>
                </div>
            </header>

            <section class="report-filter">
                <form action="report.php" method="GET" style="display: flex; align-items: center; gap: 24px; width: 100%;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <label for="gate_in_id" style="margin: 0; font-weight: 600;">Select Gate In:</label>
                        <select name="gate_in_id" id="gate_in_id" onchange="this.form.submit()" style="width: 240px;">
                            <?php foreach ($terminals as $t): ?>
                                <option value="<?php echo $t['id']; ?>" <?php echo $t['id'] == $selected_gate_in ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($t['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <label for="vendor_id" style="margin: 0; font-weight: 600;">Vendor:</label>
                        <select name="vendor_id" id="vendor_id" onchange="this.form.submit()" style="width: 240px;">
                            <option value="0" <?php echo $selected_vendor == 0 ? 'selected' : ''; ?>>All Vendors</option>
                            <?php foreach ($vendors as $v): ?>
                                <option value="<?php echo $v['id']; ?>" <?php echo $v['id'] == $selected_vendor ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($v['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <noscript><button type="submit" class="btn">View</button></noscript>
                </form>
            </section>

            <div class="card pivot-table-card">
                <table class="pivot-table">
                    <thead>
                        <tr>
                            <th>Destination \ Container</th>
                            <?php foreach ($containers as $c): ?>
                                <th><?php echo htmlspecialchars($c['reference']); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($report_rows)): ?>
                            <tr>
                                <td colspan="<?php echo count($containers) + 1; ?>" style="text-align: center; padding: 48px; color: #64748b;">
                                    No pricing data found for the selected filters.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($report_rows as $row_key => $row_info): ?>
                                <tr>
                                    <td>
                                        <?php
                                        echo htmlspecialchars($row_info['dest_name']);
                                        if ($selected_vendor == 0) {
                                            echo ' <small style="color: #64748b; font-weight: 400;">(' . htmlspecialchars($row_info['vendor_name']) . ')</small>';
                                        }
                                        ?>
                                    </td>
                                    <?php foreach ($containers as $c): ?>
                                        <td>
                                            <?php
                                            if (isset($matrix_data[$row_key][$c['id']])) {
                                                echo '<span class="price-found">' . $matrix_data[$row_key][$c['id']] . '</span>';
                                            } else {
                                                echo '<span class="price-empty">N/A</span>';
                                            }
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>

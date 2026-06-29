<?php
$db = require 'database.php';

// Fetch all terminals for the "Gate In" filter
$terminals = $db->query("SELECT * FROM terminals ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Selected Gate In
$selected_gate_in = $_GET['gate_in_id'] ?? ($terminals[0]['id'] ?? 0);

// Fetch all unique containers (Columns)
$containers = $db->query("SELECT * FROM containers ORDER BY reference ASC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all unique destinations (Rows)
$destinations = $db->query("SELECT * FROM destinations ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch pricing matrix for selected Gate In
$matrix_data = [];
if ($selected_gate_in > 0) {
    $stmt = $db->prepare("SELECT destination_id, container_id, price, currency FROM items WHERE get_in_id = ?");
    $stmt->execute([$selected_gate_in]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        $matrix_data[$row['destination_id']][$row['container_id']] = $row['currency'] . ' ' . number_format($row['price'], 2);
    }
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
                <form action="report.php" method="GET" style="display: flex; align-items: center; gap: 12px; width: 100%;">
                    <label for="gate_in_id" style="margin: 0; font-weight: 600;">Select Gate In:</label>
                    <select name="gate_in_id" id="gate_in_id" onchange="this.form.submit()">
                        <?php foreach ($terminals as $t): ?>
                            <option value="<?php echo $t['id']; ?>" <?php echo $t['id'] == $selected_gate_in ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($t['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
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
                        <?php foreach ($destinations as $d): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($d['name']); ?></td>
                                <?php foreach ($containers as $c): ?>
                                    <td>
                                        <?php
                                        if (isset($matrix_data[$d['id']][$c['id']])) {
                                            echo '<span class="price-found">' . $matrix_data[$d['id']][$c['id']] . '</span>';
                                        } else {
                                            echo '<span class="price-empty">N/A</span>';
                                        }
                                        ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>

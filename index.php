<?php
$db = require 'database.php';

// Fetch lookups for the filter datalists
$terminals = $db->query("SELECT * FROM terminals ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$destinations = $db->query("SELECT * FROM destinations ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$containers = $db->query("SELECT * FROM containers ORDER BY reference ASC")->fetchAll(PDO::FETCH_ASSOC);
$vendors = $db->query("SELECT * FROM vendors ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Capture filters from GET (now using single strings for searchable inputs)
$s_get_in = $_GET['s_get_in'] ?? '';
$s_get_out = $_GET['s_get_out'] ?? '';
$s_dest = $_GET['s_dest'] ?? '';
$s_container = $_GET['s_container'] ?? '';
$s_vendor = $_GET['s_vendor'] ?? '';

// Build Dynamic Query
$where_clauses = [];
$params = [];

if (!empty($s_get_in)) {
    $where_clauses[] = "t_in.name LIKE ?";
    $params[] = "%$s_get_in%";
}
if (!empty($s_get_out)) {
    $where_clauses[] = "t_out.name LIKE ?";
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

$query = "SELECT i.*,
                 t_in.name as get_in_name,
                 t_out.name as get_out_name,
                 d.name as destination_name,
                 c.reference as container_ref,
                 v.name as vendor_name
          FROM items i
          JOIN terminals t_in ON i.get_in_id = t_in.id
          JOIN terminals t_out ON i.get_out_id = t_out.id
          JOIN destinations d ON i.destination_id = d.id
          JOIN containers c ON i.container_id = c.id
          JOIN vendors v ON i.vendor_id = v.id";

if (!empty($where_clauses)) {
    $query .= " WHERE " . implode(" AND ", $where_clauses);
}

$query .= " ORDER BY i.id DESC";

$stmt = $db->prepare($query);
$stmt->execute($params);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EGLTRUCK - Item Master</title>
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
                    <h1>Item Master</h1>
                </div>
                <div class="page-actions">
                    <?php
                    $export_query = http_build_query([
                        'type' => 'items',
                        's_get_in' => $s_get_in,
                        's_get_out' => $s_get_out,
                        's_dest' => $s_dest,
                        's_container' => $s_container,
                        's_vendor' => $s_vendor
                    ]);
                    ?>
                    <a href="export.php?<?php echo $export_query; ?>" class="btn">Export CSV</a>
                    <a href="import.php?type=items" class="btn">Import CSV</a>
                    <a href="add.php" class="btn btn-primary">Add New Item</a>
                </div>
            </header>

            <section class="filter-section compact-search-bar">
                <form action="index.php" method="GET">
                    <input type="text" name="s_get_in" list="l_get_in" placeholder="Search Get In..." value="<?php echo htmlspecialchars($s_get_in); ?>">
                    <datalist id="l_get_in">
                        <?php foreach ($terminals as $t): ?>
                            <option value="<?php echo htmlspecialchars($t['name']); ?>">
                        <?php endforeach; ?>
                    </datalist>

                    <input type="text" name="s_get_out" list="l_get_out" placeholder="Search Get Out..." value="<?php echo htmlspecialchars($s_get_out); ?>">
                    <datalist id="l_get_out">
                        <?php foreach ($terminals as $t): ?>
                            <option value="<?php echo htmlspecialchars($t['name']); ?>">
                        <?php endforeach; ?>
                    </datalist>

                    <input type="text" name="s_dest" list="l_dest" placeholder="Search Dest..." value="<?php echo htmlspecialchars($s_dest); ?>">
                    <datalist id="l_dest">
                        <?php foreach ($destinations as $d): ?>
                            <option value="<?php echo htmlspecialchars($d['name']); ?>">
                        <?php endforeach; ?>
                    </datalist>

                    <input type="text" name="s_container" list="l_container" placeholder="Search Container..." value="<?php echo htmlspecialchars($s_container); ?>">
                    <datalist id="l_container">
                        <?php foreach ($containers as $c): ?>
                            <option value="<?php echo htmlspecialchars($c['reference']); ?>">
                        <?php endforeach; ?>
                    </datalist>

                    <input type="text" name="s_vendor" list="l_vendor" placeholder="Search Vendor..." value="<?php echo htmlspecialchars($s_vendor); ?>">
                    <datalist id="l_vendor">
                        <?php foreach ($vendors as $v): ?>
                            <option value="<?php echo htmlspecialchars($v['name']); ?>">
                        <?php endforeach; ?>
                    </datalist>

                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary">🔍</button>
                        <a href="index.php" class="btn">✖</a>
                    </div>
                </form>
            </section>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">Shipment record added successfully!</div>
            <?php endif; ?>

            <div class="card">
                <h2 class="card-title">Trucking Service Items (<?php echo count($items); ?>)</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Get In</th>
                                <th>Get Out</th>
                                <th>Destination</th>
                                <th>Container</th>
                                <th>Vendor</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($items)): ?>
                                <tr>
                                    <td colspan="8">No shipment records found matching the search criteria.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><strong>#<?php echo htmlspecialchars($item['id']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($item['get_in_name']); ?></td>
                                        <td><?php echo htmlspecialchars($item['get_out_name']); ?></td>
                                        <td><?php echo htmlspecialchars($item['destination_name']); ?></td>
                                        <td><span class="badge"><?php echo htmlspecialchars($item['container_ref']); ?></span></td>
                                        <td><?php echo htmlspecialchars($item['vendor_name']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($item['currency']); ?> <?php echo htmlspecialchars(number_format($item['price'], 2)); ?></strong></td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="edit_item.php?id=<?php echo $item['id']; ?>" class="action-link edit">Edit</a>
                                                <a href="delete.php?type=items&id=<?php echo $item['id']; ?>" class="action-link delete" onclick="return confirm('Delete this shipment?')">Delete</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

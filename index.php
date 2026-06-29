<?php
$db = require 'database.php';

// Fetch lookups for the filter dropdowns
$terminals = $db->query("SELECT * FROM terminals ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$destinations = $db->query("SELECT * FROM destinations ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$containers = $db->query("SELECT * FROM containers ORDER BY reference ASC")->fetchAll(PDO::FETCH_ASSOC);
$vendors = $db->query("SELECT * FROM vendors ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Capture filters from GET
$f_get_in = $_GET['f_get_in'] ?? [];
$f_get_out = $_GET['f_get_out'] ?? [];
$f_dest = $_GET['f_dest'] ?? [];
$f_container = $_GET['f_container'] ?? [];
$f_vendor = $_GET['f_vendor'] ?? [];

// Build Dynamic Query
$where_clauses = [];
$params = [];

if (!empty($f_get_in)) {
    $placeholders = implode(',', array_fill(0, count($f_get_in), '?'));
    $where_clauses[] = "i.get_in_id IN ($placeholders)";
    $params = array_merge($params, $f_get_in);
}

if (!empty($f_get_out)) {
    $placeholders = implode(',', array_fill(0, count($f_get_out), '?'));
    $where_clauses[] = "i.get_out_id IN ($placeholders)";
    $params = array_merge($params, $f_get_out);
}

if (!empty($f_dest)) {
    $placeholders = implode(',', array_fill(0, count($f_dest), '?'));
    $where_clauses[] = "i.destination_id IN ($placeholders)";
    $params = array_merge($params, $f_dest);
}

if (!empty($f_container)) {
    $placeholders = implode(',', array_fill(0, count($f_container), '?'));
    $where_clauses[] = "i.container_id IN ($placeholders)";
    $params = array_merge($params, $f_container);
}

if (!empty($f_vendor)) {
    $placeholders = implode(',', array_fill(0, count($f_vendor), '?'));
    $where_clauses[] = "i.vendor_id IN ($placeholders)";
    $params = array_merge($params, $f_vendor);
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

function is_selected($val, $arr) {
    return in_array($val, $arr) ? 'selected' : '';
}
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
                    <p>Overview of all active shipment routes and pricing</p>
                </div>
                <div class="page-actions">
                    <a href="export.php?type=items" class="btn">Export CSV</a>
                    <a href="import.php?type=items" class="btn">Import CSV</a>
                    <a href="add.php" class="btn btn-primary">Add New Item</a>
                </div>
            </header>

            <section class="filter-section">
                <form action="index.php" method="GET">
                    <div class="filter-grid">
                        <div class="filter-group">
                            <label>Get In</label>
                            <select name="f_get_in[]" multiple title="Hold Ctrl to select multiple">
                                <?php foreach ($terminals as $t): ?>
                                    <option value="<?php echo $t['id']; ?>" <?php echo is_selected($t['id'], $f_get_in); ?>>
                                        <?php echo htmlspecialchars($t['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Get Out</label>
                            <select name="f_get_out[]" multiple title="Hold Ctrl to select multiple">
                                <?php foreach ($terminals as $t): ?>
                                    <option value="<?php echo $t['id']; ?>" <?php echo is_selected($t['id'], $f_get_out); ?>>
                                        <?php echo htmlspecialchars($t['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Destination</label>
                            <select name="f_dest[]" multiple title="Hold Ctrl to select multiple">
                                <?php foreach ($destinations as $d): ?>
                                    <option value="<?php echo $d['id']; ?>" <?php echo is_selected($d['id'], $f_dest); ?>>
                                        <?php echo htmlspecialchars($d['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Container</label>
                            <select name="f_container[]" multiple title="Hold Ctrl to select multiple">
                                <?php foreach ($containers as $c): ?>
                                    <option value="<?php echo $c['id']; ?>" <?php echo is_selected($c['id'], $f_container); ?>>
                                        <?php echo htmlspecialchars($c['reference']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Vendor</label>
                            <select name="f_vendor[]" multiple title="Hold Ctrl to select multiple">
                                <?php foreach ($vendors as $v): ?>
                                    <option value="<?php echo $v['id']; ?>" <?php echo is_selected($v['id'], $f_vendor); ?>>
                                        <?php echo htmlspecialchars($v['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="filter-actions">
                        <a href="index.php" class="btn">Reset</a>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </section>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">Shipment record added successfully!</div>
            <?php endif; ?>
            <?php if (isset($_GET['updated'])): ?>
                <div class="alert alert-success">Shipment record updated successfully!</div>
            <?php endif; ?>
            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-danger">Record deleted successfully.</div>
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
                                    <td colspan="8">No shipment records found matching the filters.</td>
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

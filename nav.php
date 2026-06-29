<aside class="sidebar">
    <nav class="sidebar-nav">
        <ul>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <a href="index.php">
                    <span class="icon">📊</span>
                    <span class="label">Item Master</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'add.php' ? 'active' : ''; ?>">
                <a href="add.php">
                    <span class="icon">➕</span>
                    <span class="label">New Shipment</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'terminals.php' ? 'active' : ''; ?>">
                <a href="terminals.php">
                    <span class="icon">📍</span>
                    <span class="label">Terminals</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'destinations.php' ? 'active' : ''; ?>">
                <a href="destinations.php">
                    <span class="icon">🏁</span>
                    <span class="label">Destinations</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'containers.php' ? 'active' : ''; ?>">
                <a href="containers.php">
                    <span class="icon">📦</span>
                    <span class="label">Containers</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'vendors.php' ? 'active' : ''; ?>">
                <a href="vendors.php">
                    <span class="icon">🏢</span>
                    <span class="label">Vendors</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'report.php' ? 'active' : ''; ?>">
                <a href="report.php">
                    <span class="icon">📈</span>
                    <span class="label">Pricing Report</span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="sidebar-footer">
        <p>© 2024 EGLTRUCK v2.0</p>
    </div>
</aside>

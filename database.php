<?php

try {
    $db = new PDO('sqlite:trucking.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Enable foreign keys
    $db->exec("PRAGMA foreign_keys = ON;");

    // Terminals
    $db->exec("CREATE TABLE IF NOT EXISTS terminals (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE
    )");

    // Destinations
    $db->exec("CREATE TABLE IF NOT EXISTS destinations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE
    )");

    // Containers
    $db->exec("CREATE TABLE IF NOT EXISTS containers (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        reference TEXT NOT NULL UNIQUE
    )");

    // Vendors
    $db->exec("CREATE TABLE IF NOT EXISTS vendors (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE
    )");

    // Items
    $db->exec("CREATE TABLE IF NOT EXISTS items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        get_in_id INTEGER NOT NULL,
        get_out_id INTEGER NOT NULL,
        destination_id INTEGER NOT NULL,
        container_id INTEGER NOT NULL,
        vendor_id INTEGER NOT NULL,
        price REAL NOT NULL,
        FOREIGN KEY (get_in_id) REFERENCES terminals(id),
        FOREIGN KEY (get_out_id) REFERENCES terminals(id),
        FOREIGN KEY (destination_id) REFERENCES destinations(id),
        FOREIGN KEY (container_id) REFERENCES containers(id),
        FOREIGN KEY (vendor_id) REFERENCES vendors(id)
    )");
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

return $db;
?>

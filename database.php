<?php

try {
    $db = new PDO('sqlite:trucking.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create terminals table (for Get In / Get Out)
    $db->exec("CREATE TABLE IF NOT EXISTS terminals (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE
    )");

    // Create destinations table
    $db->exec("CREATE TABLE IF NOT EXISTS destinations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE
    )");

    // Create items table with relational links
    $db->exec("CREATE TABLE IF NOT EXISTS items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        get_in_id INTEGER NOT NULL,
        get_out_id INTEGER NOT NULL,
        destination_id INTEGER NOT NULL,
        container TEXT NOT NULL,
        vendor TEXT NOT NULL,
        price REAL NOT NULL,
        FOREIGN KEY (get_in_id) REFERENCES terminals(id),
        FOREIGN KEY (get_out_id) REFERENCES terminals(id),
        FOREIGN KEY (destination_id) REFERENCES destinations(id)
    )");
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

return $db;
?>

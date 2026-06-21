<?php

try {
    $db = new PDO('sqlite:trucking.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create table if it doesn't exist
    $query = "CREATE TABLE IF NOT EXISTS items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        get_in TEXT NOT NULL,
        get_out TEXT NOT NULL,
        destination TEXT NOT NULL,
        container TEXT NOT NULL,
        vendor TEXT NOT NULL,
        price REAL NOT NULL
    )";
    $db->exec($query);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

return $db;
?>

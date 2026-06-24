<?php
$db = require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');

    if (!empty($name)) {
        try {
            $stmt = $db->prepare("INSERT INTO destinations (name) VALUES (:name)");
            $stmt->execute([':name' => $name]);
            header("Location: destinations.php?success=1");
            exit;
        } catch (PDOException $e) {
            die("Error adding destination: " . $e->getMessage());
        }
    } else {
        die("Invalid input.");
    }
} else {
    header("Location: destinations.php");
    exit;
}
?>

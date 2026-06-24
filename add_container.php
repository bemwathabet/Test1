<?php
$db = require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reference = trim($_POST['reference'] ?? '');

    if (!empty($reference)) {
        try {
            $stmt = $db->prepare("INSERT INTO containers (reference) VALUES (:reference)");
            $stmt->execute([':reference' => $reference]);
            header("Location: containers.php?success=1");
            exit;
        } catch (PDOException $e) {
            die("Error adding container: " . $e->getMessage());
        }
    } else {
        die("Invalid input.");
    }
} else {
    header("Location: containers.php");
    exit;
}
?>

<?php
$db = require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $get_in = $_POST['get_in'] ?? '';
    $get_out = $_POST['get_out'] ?? '';
    $destination = $_POST['destination'] ?? '';
    $price = $_POST['price'] ?? 0;

    if (!empty($get_in) && !empty($get_out) && !empty($destination) && $price > 0) {
        try {
            $stmt = $db->prepare("INSERT INTO items (get_in, get_out, destination, price) VALUES (:get_in, :get_out, :destination, :price)");
            $stmt->execute([
                ':get_in' => $get_in,
                ':get_out' => $get_out,
                ':destination' => $destination,
                ':price' => $price
            ]);
            header("Location: index.php?success=1");
            exit;
        } catch (PDOException $e) {
            die("Error adding item: " . $e->getMessage());
        }
    } else {
        die("Invalid input data.");
    }
} else {
    header("Location: index.php");
    exit;
}
?>

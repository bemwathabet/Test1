<?php
$db = require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $get_in = $_POST['get_in'] ?? '';
    $get_out = $_POST['get_out'] ?? '';
    $destination = $_POST['destination'] ?? '';
    $container = $_POST['container'] ?? '';
    $vendor = $_POST['vendor'] ?? '';
    $price = $_POST['price'] ?? 0;

    if (!empty($get_in) && !empty($get_out) && !empty($destination) && !empty($container) && !empty($vendor) && $price > 0) {
        try {
            $stmt = $db->prepare("INSERT INTO items (get_in, get_out, destination, container, vendor, price) VALUES (:get_in, :get_out, :destination, :container, :vendor, :price)");
            $stmt->execute([
                ':get_in' => $get_in,
                ':get_out' => $get_out,
                ':destination' => $destination,
                ':container' => $container,
                ':vendor' => $vendor,
                ':price' => $price
            ]);
            header("Location: index.php?success=1");
            exit;
        } catch (PDOException $e) {
            die("Error adding item: " . $e->getMessage());
        }
    } else {
        die("Invalid input data. Please make sure all fields are filled correctly.");
    }
} else {
    header("Location: index.php");
    exit;
}
?>

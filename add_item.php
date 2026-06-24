<?php
$db = require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $get_in_id = $_POST['get_in_id'] ?? 0;
    $get_out_id = $_POST['get_out_id'] ?? 0;
    $destination_id = $_POST['destination_id'] ?? 0;
    $container = $_POST['container'] ?? '';
    $vendor = $_POST['vendor'] ?? '';
    $price = $_POST['price'] ?? 0;

    if ($get_in_id > 0 && $get_out_id > 0 && $destination_id > 0 && !empty($container) && !empty($vendor) && $price > 0) {
        try {
            $stmt = $db->prepare("INSERT INTO items (get_in_id, get_out_id, destination_id, container, vendor, price) VALUES (:get_in_id, :get_out_id, :destination_id, :container, :vendor, :price)");
            $stmt->execute([
                ':get_in_id' => $get_in_id,
                ':get_out_id' => $get_out_id,
                ':destination_id' => $destination_id,
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

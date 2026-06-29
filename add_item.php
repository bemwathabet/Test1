<?php
$db = require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $get_in_id = $_POST['get_in_id'] ?? 0;
    $get_out_id = $_POST['get_out_id'] ?? 0;
    $destination_id = $_POST['destination_id'] ?? 0;
    $container_id = $_POST['container_id'] ?? 0;
    $vendor_id = $_POST['vendor_id'] ?? 0;
    $price = $_POST['price'] ?? 0;
    $currency = $_POST['currency'] ?? 'EGP';

    if ($get_in_id > 0 && $get_out_id > 0 && $destination_id > 0 && $container_id > 0 && $vendor_id > 0 && $price > 0) {
        try {
            $stmt = $db->prepare("INSERT INTO items (get_in_id, get_out_id, destination_id, container_id, vendor_id, price, currency) VALUES (:get_in_id, :get_out_id, :destination_id, :container_id, :vendor_id, :price, :currency)");
            $stmt->execute([
                ':get_in_id' => $get_in_id,
                ':get_out_id' => $get_out_id,
                ':destination_id' => $destination_id,
                ':container_id' => $container_id,
                ':vendor_id' => $vendor_id,
                ':price' => $price,
                ':currency' => $currency
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

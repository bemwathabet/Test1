<?php
$db = require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $get_in_id = $_POST['get_in_id'] ?? 0;
    $get_out_id = $_POST['get_out_id'] ?? 0;
    $destination_id = $_POST['destination_id'] ?? 0;
    $container_id = $_POST['container_id'] ?? 0;
    $vendor_id = $_POST['vendor_id'] ?? 0;
    $price = $_POST['price'] ?? 0;

    if ($id > 0 && $get_in_id > 0 && $get_out_id > 0 && $destination_id > 0 && $container_id > 0 && $vendor_id > 0 && $price > 0) {
        try {
            $stmt = $db->prepare("UPDATE items SET
                get_in_id = :get_in_id,
                get_out_id = :get_out_id,
                destination_id = :destination_id,
                container_id = :container_id,
                vendor_id = :vendor_id,
                price = :price
                WHERE id = :id");
            $stmt->execute([
                ':get_in_id' => $get_in_id,
                ':get_out_id' => $get_out_id,
                ':destination_id' => $destination_id,
                ':container_id' => $container_id,
                ':vendor_id' => $vendor_id,
                ':price' => $price,
                ':id' => $id
            ]);
            header("Location: index.php?updated=1");
            exit;
        } catch (PDOException $e) {
            die("Error updating shipment: " . $e->getMessage());
        }
    } else {
        die("Invalid input data.");
    }
} else {
    header("Location: index.php");
    exit;
}
?>

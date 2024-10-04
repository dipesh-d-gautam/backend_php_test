<?php
require 'DbConnection.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['number']) && isset($data['orderAmount'])) {
    $number = $data['number'];
    $orderAmount = $data['orderAmount'];

    $db = new DbConnection();
    $conn = $db->getConnection();

    try {
        $sql = "UPDATE products SET order_amount = :orderAmount WHERE number = :number";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':orderAmount', $orderAmount);
        $stmt->bindValue(':number', $number);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input.']);
}

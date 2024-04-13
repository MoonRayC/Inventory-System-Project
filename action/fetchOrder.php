<?php
include 'config.php';

$orderID = $_GET['orderID'];

$stmt = $pdo->prepare("SELECT * FROM orders WHERE orderID = :orderID");
$stmt->bindParam(":orderID", $orderID);
$stmt->execute();
$orderData = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($orderData);
?>

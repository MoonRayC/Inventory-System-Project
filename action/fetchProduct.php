<?php
include 'config.php';

$stmt = $pdo->prepare("SELECT * FROM products");
$stmt->execute();
$productData = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($categoryData);
?>

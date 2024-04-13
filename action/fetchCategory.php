<?php
include 'config.php';

$categoryID = $_GET['categoryID'];

$stmt = $pdo->prepare("SELECT * FROM categories WHERE categoryID = :categoryID");
$stmt->bindParam(":categoryID", $categoryID);
$stmt->execute();
$categoryData = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($categoryData);
?>

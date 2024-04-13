<?php
// fetchBrandData.php

// Include your database configuration
include 'config.php';

// Get the brandID from the AJAX request
$brandID = $_GET['brandID'];

// Fetch brand data from the database based on brandID
$stmt = $pdo->prepare("SELECT * FROM brands WHERE brandID = :brandID");
$stmt->bindParam(":brandID", $brandID);
$stmt->execute();
$brandData = $stmt->fetch(PDO::FETCH_ASSOC);

// Return the brand data as JSON
header('Content-Type: application/json');
echo json_encode($brandData);
?>

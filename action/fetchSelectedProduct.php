<?php

require_once 'config.php';

// Assuming the productID is sent via POST request
$productID = $_POST['productID'];

// Using prepared statement to prevent SQL injection
$sql = "SELECT productID, categoryID, brandID, name, price, stocks, status FROM products WHERE productID = :productID";

// Prepare the statement
$stmt = $pdo->prepare($sql);

// Bind the parameter
$stmt->bindParam(':productID', $productID, PDO::PARAM_INT);

// Execute the query
$stmt->execute();

// Fetch the result as an associative array
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Close the database connection
$pdo = null;

// Return the result as JSON
echo json_encode($row);

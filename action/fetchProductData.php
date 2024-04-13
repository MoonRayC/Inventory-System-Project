<?php

$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "myims";

// db connection
$connect = new mysqli($localhost, $username, $password, $dbname);
// check connection
if($connect->connect_error) {
  die("Connection Failed : " . $connect->connect_error);
} else {
  // echo "Successfully connected";
}

$sql = 'SELECT productID, name FROM products WHERE status = "available"';
$stmt = $connect->query($sql);

// Fetch the results as an associative array
$data = $stmt->fetch_all();

// Close the database connection
$pdo = null;

echo json_encode($data);

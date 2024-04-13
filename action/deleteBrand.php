<?php
include 'config.php';

// Check if brand ID is provided in the URL
if (isset($_GET['id'])) {
    $brandID = $_GET['id'];  

     // Prepare and execute the UPDATE query for products table
     $stmtUpdateProduct = $pdo->prepare("UPDATE products SET brandID = NULL WHERE brandID = ?");
     $stmtUpdateProduct->execute([$brandID]);

    // Prepare and execute the DELETE query
    $stmt = $pdo->prepare("DELETE FROM brands WHERE brandID = ?");
    $stmt->execute([$brandID]);
    // Redirect back to the brands page after deletion
    header("Location: http://localhost/WebIMS/brand.php?brandID=$brandId&success=4");
    exit();
} else {
    // Redirect to the brands page if brand ID is not provided
    header("Location: http://localhost/WebIMS/brand.php?brandID=$brandId&success=5");
    exit();
}
?>
<?php
include 'config.php';

// Check if brand ID is provided in the URL
if (isset($_GET['id'])) {
    $productID = $_GET['id'];   

    // Prepare and execute the DELETE query
    $stmt = $pdo->prepare("DELETE FROM products WHERE productID = ?");
    $stmt->execute([$productID]);
    // Redirect back to the brands page after deletion
    header("Location: http://localhost/WebIMS/product.php?productID=$productID&success=4");
    exit();
} else {
    // Redirect to the brands page if brand ID is not provided
    header("Location: http://localhost/WebIMS/product.php?productID=$productID&success=5");
    exit();
}
?>
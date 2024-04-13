<?php
include 'config.php';

// Check if category ID is provided in the URL
if (isset($_GET['id'])) {
    $categoryID = $_GET['id'];

     // Prepare and execute the UPDATE query for products table
     $stmtUpdateProduct = $pdo->prepare("UPDATE products SET categoryID = NULL WHERE categoryID = ?");
     $stmtUpdateProduct->execute([$categoryID]);

    // Prepare and execute the DELETE query for categories table
    $stmtDeleteCategory = $pdo->prepare("DELETE FROM categories WHERE categoryID = ?");
    $stmtDeleteCategory->execute([$categoryID]);

    header("Location: http://localhost/WebIMS/category.php?categoryID=$categoryID&success=4");
    exit();
} else {
    // Redirect to the categories page if category ID is not provided
    header("Location: http://localhost/WebIMS/category.php?success=5");
    exit();
}
?>

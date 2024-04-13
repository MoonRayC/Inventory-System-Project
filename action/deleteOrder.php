<?php
include 'config.php';

if (isset($_GET['id'])) {
    $orderID = $_GET['id'];

     $stmtUpdateProduct = $pdo->prepare("DELETE FROM orders WHERE orderID = ?");
     $stmtUpdateProduct->execute([$orderID]);

    $stmtDeleteCategory = $pdo->prepare("DELETE FROM orderedItems WHERE orderID = ?");
    $stmtDeleteCategory->execute([$orderID]);

    header("Location: http://localhost/WebIMS/order.php?categoryID=$categoryID&success=4");
    exit();
} else {
    // Redirect to the categories page if category ID is not provided
    header("Location: http://localhost/WebIMS/order.php?success=5");
    exit();
}
?>

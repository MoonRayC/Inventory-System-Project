<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $categoryID = trim($_POST['categoryID']);
    $name = trim($_POST['name']);
    $status = trim($_POST['status']);

    $updateCategorySql = 'UPDATE categories SET name = :name, status = :status WHERE categoryID = :categoryID';
    $updateCategoryStmt = $pdo->prepare($updateCategorySql);
    $updateCategoryStmt->bindParam(':name', $name, PDO::PARAM_STR);
    $updateCategoryStmt->bindParam(':status', $status, PDO::PARAM_STR);
    $updateCategoryStmt->bindParam(':categoryID', $categoryID, PDO::PARAM_INT);
    // Execute the update and check for success
    $updateSuccess = $updateCategoryStmt->execute();

    if ($updateSuccess) {
        header("location: http://localhost/WebIMS/category.php?success=2");
        exit();
    } else {
        header("location: http://localhost/WebIMS/category.php?success=3");
        exit();
    } 
}
?>
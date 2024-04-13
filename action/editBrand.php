<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brandID = trim($_POST['brandID']);
    $name = trim($_POST['name']);
    $status = trim($_POST['status']);

    $updateBrandSql = 'UPDATE brands SET name = :name, status = :status WHERE brandID = :brandID';
    $updateBrandStmt = $pdo->prepare($updateBrandSql);
    $updateBrandStmt->bindParam(':name', $name, PDO::PARAM_STR);
    $updateBrandStmt->bindParam(':status', $status, PDO::PARAM_STR);
    $updateBrandStmt->bindParam(':brandID', $brandID, PDO::PARAM_INT);

    $updateSuccess = $updateBrandStmt->execute();

    if ($updateSuccess) {
        header("location: http://localhost/WebIMS/brand.php?success=2");
        exit();
    } else {
        header("location: http://localhost/WebIMS/brand.php?success=3");
        exit();
    } 
}
?>
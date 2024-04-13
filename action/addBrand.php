<?php
// Include the necessary files (config.php and any other required files)
include 'config.php';

// Start the session
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_err = "";

    if (empty(trim($_POST["brandName"]))) {
        $brand_err = "Please enter a brand name.";
    } else {
        $brandName = trim($_POST["brandName"]);
    } 

    if (empty($brand_err)) {
        $addBrandSql = 'INSERT INTO brands (name, stocks, status) VALUES (:brandName, 0, "unavailable")';
        $addBrandStmt = $pdo->prepare($addBrandSql);
        $addBrandStmt->bindParam(':brandName', $brandName, PDO::PARAM_STR);

        $updateSuccess = $addBrandStmt->execute();

        if ($updateSuccess) {
            header("location: http://localhost/WebIMS/brand.php?success=1");
            exit();
        } else {
            header("location: http://localhost/WebIMS/brand.php?success=0");
            exit();
        }
    } else {
        $_SESSION['brand_err'] = $brand_err;
        header("location: http://localhost/WebIMS/brand.php");
        exit();
    }
}
?>

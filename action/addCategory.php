<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $category_err = "";

    if (empty(trim($_POST["categoryName"]))) {
        $category_err = "Please enter a category name.";
    } else {
        $categoryName = trim($_POST["categoryName"]);
    } 

    if (empty($name_err)) {
        $addCategorySql = 'INSERT INTO categories (name, stocks, status) VALUES (:categoryName, 0, "unavailable")';
        $addCategoryStmt = $pdo->prepare($addCategorySql);
        $addCategoryStmt->bindParam(':categoryName', $categoryName, PDO::PARAM_STR);

        $updateSuccess = $addCategoryStmt->execute();

        if ($updateSuccess) {
            header("location: http://localhost/WebIMS/category.php?success=1");
            exit();
        } else {
            header("location: http://localhost/WebIMS/category.php?success=0");
            exit();
        }
    } else {
        $_SESSION['category_err'] = $category_err;
        header("location: http://localhost/WebIMS/category.php");
        exit();
    }
}
?>
<?php
include 'config.php';

session_start();

if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    header('location: login.php');
    exit();
}

$stmtBrands = $pdo->prepare("SELECT brandID, name, stocks FROM brands");  
$stmtBrands->execute();
$brands = $stmtBrands->fetchAll(PDO::FETCH_ASSOC);

$stmtCategories = $pdo->prepare("SELECT categoryID, name, stocks FROM categories");
$stmtCategories->execute();
$categories = $stmtCategories->fetchAll(PDO::FETCH_ASSOC);

$userType = $_SESSION['user_type'];

$productName = $brand = $category = $price = $stocks = $supplierName = $status = '';
$productName_err = $brand_err = $category_err  = $price_err = $stocks_err = $supplierName_err = $status_err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty(trim($_POST['productName']))) {
        $name_err = 'Please Enter a Product Name.';
    } else {
        $productName = trim($_POST['productName']);
    }

    if (empty(trim($_POST['brand']))) {
        $brand_err = 'Please Enter Product Brand.';
    } else {
        $brand = trim($_POST['brand']);
    }

    if (empty(trim($_POST['category']))) {
        $category_err = 'Please Enter Product Category.';
    } else {
        $category = trim($_POST['category']);
    }

    if (empty(trim($_POST['price']))) {
        $price_err = 'Please Enter a price.';
    } else {
        $price = trim($_POST['price']);
    }

    if (empty(trim($_POST['stocks']))) {
        $stocks_err = 'Please Enter stock value.';
    } else {
        $stocks = trim($_POST['stocks']);
    }

    if (empty(trim($_POST['supplierName']))) {
        $supplierName_err = 'Please Enter Supplier Name.';
    } else {
        $supplierName = trim($_POST['supplierName']);
    }

    if (empty(trim($_POST['status']))) {
        $status_err = 'Please Enter Product Status.';
    } else {
        $status = trim($_POST['status']);
    }
   // Fetch brand data from the database based on brandID
    $stmt = $pdo->prepare("SELECT * FROM brands WHERE brandID = :brand");
    $stmt->bindParam(":brand", $brand, PDO::PARAM_INT);
    $stmt->execute();
    $brandData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch category data from the database based on categoryID
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE categoryID = :category");
    $stmt->bindParam(":category", $category, PDO::PARAM_INT);
    $stmt->execute();
    $categoryData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($name_err) && empty($brand_err) && empty($category_err) && empty($price_err) && empty($stocks_err) && empty($supplierName_err) && empty($status_err)){

        $addProductSql = 'INSERT INTO products (name, brandID, categoryID, price, stocks, supplier, status) 
                        VALUES (:productName, :brand, :category, :price, :stocks, :supplierName, :status)';
        $addProductStmt = $pdo->prepare($addProductSql);
        $addProductStmt->bindParam(':productName', $productName, PDO::PARAM_STR);
        $addProductStmt->bindParam(':brand', $brand, PDO::PARAM_INT);
        $addProductStmt->bindParam(':category', $category, PDO::PARAM_INT);
        $addProductStmt->bindParam(':price', $price, PDO::PARAM_INT);
        $addProductStmt->bindParam(':stocks', $stocks, PDO::PARAM_INT);
        $addProductStmt->bindParam(':supplierName', $supplierName, PDO::PARAM_STR);
        $addProductStmt->bindParam(':status', $status, PDO::PARAM_STR);

        // Execute the update and check for success
        $updateSuccess = $addProductStmt->execute();

        if ($updateSuccess) {
            // Calculate the new total stocks for brands and categories
            $totalBrandStocks = (int)$brandData['stocks'] + (int)$stocks;
            $totalCategoryStocks = (int)$categoryData['stocks'] + (int)$stocks;

            // Determine status based on stocks
            $brandStatus = ($stocks >= 1) ? "available" : "unavailable";
            $categoryStatus = ($stocks >= 1) ? "available" : "unavailable";

            // Update brand stocks and status
            $updateBrandStocksSql = 'UPDATE brands SET stocks = :totalBrandStocks, status = :brandStatus WHERE brandID = :brand';
            $updateBrandStocksStmt = $pdo->prepare($updateBrandStocksSql);
            $updateBrandStocksStmt->bindParam(':totalBrandStocks', $totalBrandStocks, PDO::PARAM_INT);
            $updateBrandStocksStmt->bindParam(':brandStatus', $brandStatus, PDO::PARAM_STR);
            $updateBrandStocksStmt->bindParam(':brand', $brand, PDO::PARAM_INT);
            $updateBrandStocksStmt->execute();

            // Update category stocks and status
            $updateCategoriesStocksSql = 'UPDATE categories SET stocks = :totalCategoryStocks, status = :categoryStatus WHERE categoryID = :category';
            $updateCategoriesStocksStmt = $pdo->prepare($updateCategoriesStocksSql);
            $updateCategoriesStocksStmt->bindParam(':totalCategoryStocks', $totalCategoryStocks, PDO::PARAM_INT);
            $updateCategoriesStocksStmt->bindParam(':categoryStatus', $categoryStatus, PDO::PARAM_STR);
            $updateCategoriesStocksStmt->bindParam(':category', $category, PDO::PARAM_INT);
            $updateCategoriesStocksStmt->execute();

            header("location: product.php?success=1");
            exit();
        } else {
            header("location: product.php?success=0");
            exit();
        }
    }
}
?>


<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <script src="assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RDCJ IMS</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/sidebars/">



    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/sideIcon.css">
    <link href="styles/indexStyle.css" rel="stylesheet">
</head>

<body>
    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="house-door-fill" viewBox="0 0 16 16">
            <path
                d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z" />
        </symbol>
    </svg>
    <?php include "includes/bgtheme.php" ?>

    <main class="d-flex flex-nowrap">
        <?php include "includes/sidebar.php" ?>


        <div class="b-example-divider b-example-vr"></div>
        <div class="container">
            <nav class="navbar bg-body">
                <div class="container-fluid">

                    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="link-body-emphasis" href="index.php">
                                    <svg class="bi" width="16" height="16">
                                        <use xlink:href="#house-door-fill"></use>
                                    </svg>
                                    <span class="visually-hidden">Home</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item"><a href="product.php">Product</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Product</li>
                        </ol>
                    </nav>

                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
            </nav>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Add Product</h5>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">

                        <div class="form-floating">
                            <input type="text" name="productName" id="productName"
                                class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>"
                                value="<?php echo $productName; ?>">
                            <label for="floatingInput">Product Name</label>
                            <span class="invalid-feedback"><?php echo $name_err; ?></span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="brand" id="" brand
                                        class="form-select <?php echo (!empty($brand_err)) ? 'is-invalid' : ''; ?>">
                                        <option value="">~~SELECT~~</option>
                                        <?php foreach ($brands as $brand) : ?>
                                        <option value="<?php echo $brand['brandID']; ?>">
                                            <?php echo $brand['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="brand">Brand</label>
                                    <span class="invalid-feedback"><?php echo $brand_err; ?></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="category" id="category"
                                        class="form-select <?php echo (!empty($category_err)) ? 'is-invalid' : ''; ?>">
                                        <option value="">~~SELECT~~</option>
                                        <?php foreach ($categories as $category) : ?>
                                        <option value="<?php echo $category['categoryID']; ?>">
                                            <?php echo $category['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="category">Category</label>
                                    <span class="invalid-feedback"><?php echo $category_err; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" name="price" id="price"
                                        class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>"
                                        value="<?php echo $price; ?>">
                                    <label for="floatingInput">Price</label>
                                    <span class="invalid-feedback"><?php echo $price_err; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" name="stocks" id="stocks"
                                        class="form-control <?php echo (!empty($stocks_err)) ? 'is-invalid' : ''; ?>"
                                        value="<?php echo $stocks; ?>">
                                    <label for="floatingInput">Stocks</label>
                                    <span class="invalid-feedback"><?php echo $stocks_err; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating">
                            <input type="text" name="supplierName" id="supplierName"
                                class="form-control <?php echo (!empty($supplierName_err)) ? 'is-invalid' : ''; ?>"
                                value="<?php echo $supplierName; ?>">
                            <label for="floatingInput">Supplier Name</label>
                            <span class="invalid-feedback"><?php echo $supplierName_err; ?></span>
                        </div>

                        <div class="form-floating">
                            <select name="status" id="status"
                                class="form-select <?php echo (!empty($status_err)) ? 'is-invalid' : ''; ?>">
                                <option value="">~~SELECT~~</option>
                                <option value="available">
                                    Available
                                </option>
                                <option value="unavailable">
                                    Unavailable
                                </option>
                            </select>
                            <label for="status">Status</label>
                            <span class="invalid-feedback"><?php echo $status_err; ?></span>
                        </div>

                        <div class="row">
                            <div class="col-md-6 d-flex justify-content-start">
                                <button type="submit" class="btn btn-primary me-2">Add Category</button>
                                <a href="product.php" class="btn btn-danger">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="sidebars.js"></script>

    <script>
    // Initialize Bootstrap tooltips
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    </script>

</body>

</html>